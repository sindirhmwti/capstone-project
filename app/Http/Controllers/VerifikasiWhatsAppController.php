<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Langkah verifikasi nomor WhatsApp (OTP) di akhir registrasi klien / calon pekerja.
 * Data registrasi sementara disimpan di session oleh AuthController::register().
 */
class VerifikasiWhatsAppController extends Controller
{
    /** Kunci session untuk data registrasi yang menunggu verifikasi. */
    public const SESI = 'registrasi_tertunda';

    public function __construct(private WhatsAppOtpService $otp)
    {
    }

    /** Halaman "Verifikasi Nomor WhatsApp". */
    public function show(Request $request)
    {
        $sesi = $this->ambilSesi($request);
        if (! $sesi) {
            return $this->sesiBerakhir($request);
        }

        return view('public.verifikasi-whatsapp', [
            'nomorSamaran' => WhatsAppOtpService::samarkan($sesi['data']['no_telepon']),
            'panjangKode' => (int) config('otp.panjang', 4),
            'sisaKirimUlang' => $this->sisaKirimUlang($sesi),
            'sisaKunci' => $this->sisaKunci($sesi),
            'sisaPercobaan' => $this->maksPercobaan() - $sesi['percobaan'],
        ]);
    }

    /** Memeriksa kode OTP (dipanggil lewat fetch/AJAX atau form POST biasa). */
    public function verifikasi(Request $request)
    {
        $sesi = $this->ambilSesi($request);
        if (! $sesi) {
            return $this->sesiBerakhir($request);
        }

        // Masih terkunci karena terlalu banyak percobaan salah
        if ($sisa = $this->sisaKunci($sesi)) {
            return $this->gagal($request, 'Terlalu banyak percobaan. Coba lagi dalam ' . $sisa . ' detik.', 0, $sisa, 423);
        }

        // Kode dari 4 kotak digit (digit[]) atau satu field "kode"
        $kode = $request->has('digit') ? implode('', (array) $request->input('digit')) : (string) $request->input('kode');
        $kode = preg_replace('/\D/', '', $kode);

        if (strlen($kode) !== (int) config('otp.panjang', 4)) {
            return $this->gagal($request, 'Masukkan ' . config('otp.panjang', 4) . ' digit kode verifikasi.', $this->maksPercobaan() - $sesi['percobaan'], 0, 422);
        }

        if (! $this->otp->verifikasiKode($sesi['data']['no_telepon'], $kode)) {
            $sesi['percobaan']++;
            $sisaPercobaan = $this->maksPercobaan() - $sesi['percobaan'];

            if ($sisaPercobaan <= 0) {
                // Kunci sementara, lalu percobaan dihitung ulang dari awal
                $sesi['kunci_sampai'] = now()->timestamp + (int) config('otp.lama_kunci_detik', 60);
                $sesi['percobaan'] = 0;
                $request->session()->put(self::SESI, $sesi);
                $lama = (int) config('otp.lama_kunci_detik', 60);

                return $this->gagal($request, 'Terlalu banyak percobaan salah. Verifikasi dikunci selama ' . $lama . ' detik.', 0, $lama, 423);
            }

            $request->session()->put(self::SESI, $sesi);

            return $this->gagal($request, 'Kode verifikasi salah. Sisa percobaan: ' . $sisaPercobaan, $sisaPercobaan, 0, 422);
        }

        // Jaga-jaga: email/nomor bisa saja keburu dipakai akun lain selama menunggu verifikasi
        $sudahDipakai = Pengguna::where('email', $sesi['data']['email'])
            ->orWhereIn('no_telepon', WhatsAppOtpService::variasi($sesi['data']['no_telepon']))->exists();
        if ($sudahDipakai) {
            $request->session()->forget(self::SESI);
            $pesan = 'Email atau nomor WhatsApp sudah terdaftar. Silakan daftar ulang dengan data lain.';

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'pesan' => $pesan, 'redirect' => route('register')], 409)
                : redirect()->route('register')->with('error', $pesan);
        }

        // Kode benar: buat akun seperti alur registrasi sebelumnya
        [$pengguna, $tujuan, $pesan] = $this->buatAkun($sesi['data']);

        $request->session()->forget(self::SESI);
        Auth::login($pengguna);
        $request->session()->regenerate();
        $request->session()->flash('success', $pesan);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'pesan' => 'Nomor berhasil diverifikasi!', 'redirect' => $tujuan]);
        }

        return redirect()->to($tujuan);
    }

    /** Kirim ulang kode (mode demo: hanya mereset timer). */
    public function kirimUlang(Request $request)
    {
        $sesi = $this->ambilSesi($request);
        if (! $sesi) {
            return $this->sesiBerakhir($request);
        }

        if ($sisa = $this->sisaKirimUlang($sesi)) {
            $pesan = 'Tunggu ' . $sisa . ' detik sebelum mengirim ulang kode.';

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'pesan' => $pesan, 'tunggu' => $sisa], 429)
                : redirect()->route('register.verifikasi')->with('error', $pesan);
        }

        $this->otp->kirimKode($sesi['data']['no_telepon']);
        $sesi['kirim_terakhir'] = now()->timestamp;
        $request->session()->put(self::SESI, $sesi);

        $pesan = 'Kode baru telah dikirim ke WhatsApp Anda.';
        $jeda = (int) config('otp.jeda_kirim_ulang_detik', 60);

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'pesan' => $pesan, 'tunggu' => $jeda])
            : redirect()->route('register.verifikasi')->with('success', $pesan);
    }

    /** Kembali ke formulir registrasi dengan data sebelumnya (kecuali kata sandi). */
    public function ubahNomor(Request $request)
    {
        $sesi = $this->ambilSesi($request);
        if (! $sesi) {
            return $this->sesiBerakhir($request);
        }

        $data = $sesi['data'];
        unset($data['kata_sandi']);
        // Tampilkan nomor dalam format lokal 08... agar mudah diubah
        $data['no_telepon'] = '0' . substr($data['no_telepon'], 2);
        $request->session()->forget(self::SESI);

        $tujuan = $data['peran'] === Pengguna::PERAN_PEKERJA
            ? route('register.pekerja.form', $data['kategori_pekerjaan'])
            : route('register.klien');

        return redirect()->to($tujuan)->withInput($data)
            ->with('info', 'Silakan perbarui nomor WhatsApp Anda, lalu isi ulang kata sandi.');
    }

    /* ─── Bantuan ─────────────────────────────────────────────────── */

    /** Data sementara di session; null bila tidak ada atau sudah kedaluwarsa (10 menit). */
    private function ambilSesi(Request $request): ?array
    {
        $sesi = $request->session()->get(self::SESI);
        if (! is_array($sesi) || empty($sesi['data'])) {
            return null;
        }

        $batas = (int) config('otp.kedaluwarsa_menit', 10) * 60;
        if (now()->timestamp - (int) $sesi['dibuat'] > $batas) {
            $request->session()->forget(self::SESI);

            return null;
        }

        return $sesi;
    }

    private function sesiBerakhir(Request $request)
    {
        $pesan = 'Sesi verifikasi berakhir, silakan daftar ulang.';

        return $request->expectsJson()
            ? response()->json(['ok' => false, 'pesan' => $pesan, 'redirect' => route('register')], 410)
            : redirect()->route('register')->with('error', $pesan);
    }

    private function gagal(Request $request, string $pesan, int $sisaPercobaan, int $sisaKunci, int $status)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false, 'pesan' => $pesan, 'sisa_percobaan' => $sisaPercobaan, 'kunci' => $sisaKunci,
            ], $status);
        }

        return redirect()->route('register.verifikasi')->withErrors(['kode' => $pesan]);
    }

    private function maksPercobaan(): int
    {
        return (int) config('otp.maks_percobaan', 5);
    }

    private function sisaKunci(array $sesi): int
    {
        return max(0, (int) ($sesi['kunci_sampai'] ?? 0) - now()->timestamp);
    }

    private function sisaKirimUlang(array $sesi): int
    {
        return max(0, (int) config('otp.jeda_kirim_ulang_detik', 60) - (now()->timestamp - (int) $sesi['kirim_terakhir']));
    }

    /**
     * Membuat akun (dan profil pekerja) persis seperti alur registrasi sebelumnya.
     *
     * @return array{0: Pengguna, 1: string, 2: string} [pengguna, url tujuan, pesan sukses]
     */
    private function buatAkun(array $data): array
    {
        $isPekerja = $data['peran'] === Pengguna::PERAN_PEKERJA;

        $pengguna = DB::transaction(function () use ($data, $isPekerja) {
            $pengguna = Pengguna::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'no_telepon' => $data['no_telepon'],
                'kata_sandi' => $data['kata_sandi'], // sudah berupa hash
                'peran' => $data['peran'],
                'status_akun' => 'aktif',
            ]);

            if ($isPekerja) {
                $pengguna->profilPekerja()->create([
                    'kategori_pekerjaan' => $data['kategori_pekerjaan'],
                    'alamat' => $data['alamat'],
                    'keterampilan' => $data['keterampilan'] ?? null,
                    'deskripsi_profil' => $data['deskripsi_profil'] ?? null,
                    'status_verifikasi' => 'belum_lengkap',
                ]);

                Notifikasi::kirim($pengguna->id_pengguna, 'Selamat datang di Temukerja',
                    'Lengkapi profil, kriteria khusus, dan dokumen persyaratan Anda agar dapat diverifikasi admin.');
                Notifikasi::kirimKeAdmin('Calon pekerja baru mendaftar',
                    $pengguna->nama . ' mendaftar sebagai ' . ProfilPekerja::KATEGORI_SINGKAT[$data['kategori_pekerjaan']] . '.');
            } else {
                Notifikasi::kirim($pengguna->id_pengguna, 'Selamat datang di Temukerja',
                    'Akun klien Anda berhasil dibuat. Silakan cari pekerja yang sesuai kebutuhan Anda.');
            }

            return $pengguna;
        });

        // Baby Sitter dan Perawat Lansia langsung diarahkan untuk mengunggah dokumen (seperti sebelumnya)
        if ($isPekerja && in_array($data['kategori_pekerjaan'], ProfilPekerja::WAJIB_SERTIFIKAT, true)) {
            return [$pengguna, route('worker.verification'),
                'Registrasi berhasil! Silakan unggah KTP dan sertifikat pelatihan untuk diverifikasi admin.'];
        }

        return [$pengguna, route($pengguna->routeDashboard()), 'Registrasi berhasil! Selamat datang di Temukerja.'];
    }
}
