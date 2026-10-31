<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** Halaman login (satu halaman untuk admin, klien, dan pekerja). */
    public function showLogin()
    {
        return view('public.login');
    }

    /** Halaman pilih akun: Klien atau Pekerja. */
    public function showSelectRole()
    {
        return view('public.select-role');
    }

    /** Halaman pilih jenis pekerja: ART, Baby Sitter, atau Perawat Lansia. */
    public function showSelectCategory()
    {
        return view('public.select-category');
    }

    /** Formulir registrasi klien. */
    public function showRegisterKlien()
    {
        return view('public.register', ['peran' => Pengguna::PERAN_KLIEN, 'kategori' => null]);
    }

    /** Formulir registrasi pekerja sesuai kategori. */
    public function showRegisterPekerja(string $kategori)
    {
        abort_unless(array_key_exists($kategori, ProfilPekerja::KATEGORI), 404);

        return view('public.register', ['peran' => Pengguna::PERAN_PEKERJA, 'kategori' => $kategori]);
    }

    /** Memproses login. */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Batasi percobaan login: 5 kali per menit per email + IP
        $kunci = Str::lower($data['email']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($kunci)]),
            ]);
        }

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($kunci);

            throw ValidationException::withMessages(['email' => 'Email atau kata sandi salah.']);
        }

        RateLimiter::clear($kunci);
        /** @var Pengguna $pengguna */
        $pengguna = Auth::user();

        // Akun nonaktif tidak boleh masuk
        if (! $pengguna->isAktif()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withInput($request->only('email'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin PT Shaka Abadi Nusantara.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route($pengguna->routeDashboard()))
            ->with('success', 'Selamat datang kembali, ' . $pengguna->nama . '!');
    }

    /** Memproses registrasi klien maupun calon pekerja. */
    public function register(Request $request)
    {
        $isPekerja = $request->input('peran') === Pengguna::PERAN_PEKERJA;

        $aturan = [
            // Peran admin ditolak walaupun formulir dimanipulasi
            'peran' => ['required', Rule::in([Pengguna::PERAN_KLIEN, Pengguna::PERAN_PEKERJA])],
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pengguna,email'],
            'no_telepon' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'kata_sandi' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms' => ['accepted'],
        ];

        if ($isPekerja) {
            $aturan += [
                'kategori_pekerjaan' => ['required', Rule::in(array_keys(ProfilPekerja::KATEGORI))],
                'alamat' => ['required', 'string', 'max:255'],
                'keterampilan' => ['nullable', 'string', 'max:255'],
                'deskripsi_profil' => ['nullable', 'string', 'max:1000'],
            ];
        }

        $data = $request->validate($aturan, [
            'no_telepon.regex' => 'Nomor telepon harus diawali 08 / 62 / +62 dan berisi 10-15 angka.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        $pengguna = DB::transaction(function () use ($data, $isPekerja) {
            $pengguna = Pengguna::create([
                'nama' => $data['nama'],
                'email' => Str::lower($data['email']),
                'no_telepon' => $data['no_telepon'],
                'kata_sandi' => $data['kata_sandi'],
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

        Auth::login($pengguna);
        $request->session()->regenerate();

        // Baby Sitter dan Perawat Lansia langsung diarahkan untuk mengunggah dokumen
        if ($isPekerja && in_array($data['kategori_pekerjaan'], ProfilPekerja::WAJIB_SERTIFIKAT, true)) {
            return redirect()->route('worker.verification')
                ->with('success', 'Registrasi berhasil! Silakan unggah KTP dan sertifikat pelatihan untuk diverifikasi admin.');
        }

        return redirect()->route($pengguna->routeDashboard())
            ->with('success', 'Registrasi berhasil! Selamat datang di Temukerja.');
    }

    /** Mengakhiri sesi pengguna. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
