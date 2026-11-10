<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalInterview;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use App\Services\KonfirmasiInterviewService;
use App\Services\PenyaluranService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Persetujuan pesanan + jadwal interview, undangan via WhatsApp (wa.me), dan pencatatan jawaban.
 */
class InterviewController extends Controller
{
    public function __construct(private KonfirmasiInterviewService $layanan)
    {
    }

    /**
     * Tombol "Setujui": ubah status menjadi disetujui (alur lama: riwayat + notifikasi), simpan jadwal,
     * kirim notifikasi website, catat "Jadwal interview dibuat", lalu kembali ke panel undangan.
     */
    public function setujui(Request $request, Pemesanan $pemesanan)
    {
        $pemesanan->load(['klien', 'profilPekerja.pengguna']);

        if (! in_array('disetujui', $pemesanan->statusBerikutnyaAdmin(), true)) {
            return back()->with('error', 'Pesanan berstatus "' . $pemesanan->labelStatus() . '" tidak dapat disetujui.');
        }

        $data = $this->validasiJadwal($request);

        // Pekerja yang sedang bertugas tidak dapat disetujui untuk pesanan lain (sama seperti alur lama)
        $bentrok = Pemesanan::where('id_pekerja', $pemesanan->id_pekerja)
            ->where('status_pemesanan', 'disetujui')
            ->whereKeyNot($pemesanan->getKey())
            ->exists();
        if ($bentrok) {
            return back()->with('error', 'Pekerja masih bertugas pada pesanan lain yang disetujui. Selesaikan pesanan tersebut terlebih dahulu.');
        }

        $admin = $request->user();
        $kode = $pemesanan->kode();

        DB::transaction(function () use ($pemesanan, $data, $admin, $kode) {
            $pemesanan->ubahStatus('disetujui', $admin->id_pengguna);
            $jadwal = $pemesanan->jadwalInterview()->create($data + ['dibuat_oleh' => $admin->id_pengguna]);
            $jadwal->setRelation('pemesanan', $pemesanan);

            // Notifikasi lama saat pesanan disetujui (isi sama seperti sebelumnya)
            Notifikasi::kirim($pemesanan->id_klien, 'Status pemesanan Anda diperbarui',
                'Pesanan ' . $kode . ' untuk ' . $pemesanan->profilPekerja->pengguna->nama . ' kini berstatus "' . $pemesanan->labelStatus() . '".');
            Notifikasi::kirim($pemesanan->profilPekerja->id_pengguna, 'Informasi penempatan ' . $kode,
                'Anda dijadwalkan bekerja untuk klien ' . $pemesanan->klien->nama . ' mulai ' . $pemesanan->tanggal_mulai?->translatedFormat('d F Y') . ' di ' . $pemesanan->lokasi_kerja . '. Admin akan menghubungi Anda.');

            // Notifikasi undangan interview
            foreach ([$pemesanan->id_klien, $pemesanan->profilPekerja->id_pengguna] as $idPengguna) {
                Notifikasi::kirim($idPengguna, 'Pesanan disetujui',
                    'Pesanan disetujui. Admin akan mengirim undangan interview melalui WhatsApp. Jadwal: ' . $jadwal->labelTanggal() . ', ' . $jadwal->labelWaktu() . '.');
            }

            $this->layanan->catatRiwayat($jadwal, $admin->id_pengguna,
                'Jadwal interview dibuat: ' . $jadwal->labelTanggal() . ', ' . $jadwal->labelWaktu() . ' (' . $jadwal->labelMetode() . ').');
        });

        return redirect()->to(route('admin.orders.show', $pemesanan) . '#jadwal-interview')
            ->with('success', 'Pesanan ' . $kode . ' disetujui dan jadwal interview tersimpan. Kirim undangan ke klien dan calon pekerja via WhatsApp.');
    }

    /**
     * "Ubah Jadwal": konfirmasi kedua pihak kembali "menunggu" dan undangan berikutnya diawali
     * "PERUBAHAN JADWAL INTERVIEW". Pesanan disetujui yang belum punya jadwal (data lama) dibuatkan jadwal.
     */
    public function simpanJadwal(Request $request, Pemesanan $pemesanan)
    {
        $pemesanan->load(['klien', 'profilPekerja.pengguna', 'jadwalInterview']);

        if ($pemesanan->status_pemesanan !== 'disetujui') {
            return back()->with('error', 'Jadwal interview hanya dapat diatur untuk pesanan yang berstatus Disetujui.');
        }

        $data = $this->validasiJadwal($request);
        $admin = $request->user();
        $jadwal = $pemesanan->jadwalInterview;
        $perubahan = (bool) $jadwal;

        DB::transaction(function () use (&$jadwal, $pemesanan, $data, $admin, $perubahan) {
            if ($perubahan) {
                $jadwal->fill($data);
                $jadwal->resetKonfirmasi();
                $jadwal->jadwal_diubah_pada = now();
                $jadwal->save();
            } else {
                $jadwal = $pemesanan->jadwalInterview()->create($data + ['dibuat_oleh' => $admin->id_pengguna]);
            }
            $jadwal->setRelation('pemesanan', $pemesanan);
            $ringkas = $jadwal->labelTanggal() . ', ' . $jadwal->labelWaktu() . ' (' . $jadwal->labelMetode() . ')';

            foreach ([$pemesanan->id_klien, $pemesanan->profilPekerja->id_pengguna] as $idPengguna) {
                Notifikasi::kirim($idPengguna,
                    $perubahan ? 'Jadwal interview diperbarui' : 'Jadwal interview ditetapkan',
                    ($perubahan ? 'Jadwal interview pesanan ' . $pemesanan->kode() . ' diperbarui menjadi ' : 'Jadwal interview pesanan ' . $pemesanan->kode() . ': ')
                    . $ringkas . '. Admin akan mengirim undangan melalui WhatsApp.');
            }

            $this->layanan->catatRiwayat($jadwal, $admin->id_pengguna,
                ($perubahan ? 'Jadwal interview diubah menjadi ' : 'Jadwal interview dibuat: ') . $ringkas . '.');
        });

        return redirect()->to(route('admin.orders.show', $pemesanan) . '#jadwal-interview')
            ->with('success', $perubahan
                ? 'Jadwal interview diperbarui. Konfirmasi kedua pihak kembali "Menunggu"; kirim undangan perubahan jadwal via WhatsApp.'
                : 'Jadwal interview tersimpan. Kirim undangan ke klien dan calon pekerja via WhatsApp.');
    }

    /**
     * "Kirim Undangan via WhatsApp": catat waktu undangan, panggil kirimPesan() (demo: log),
     * lalu arahkan tab baru ke wa.me dengan pesan undangan terisi otomatis.
     */
    public function kirimUndangan(Request $request, Pemesanan $pemesanan, string $pihak)
    {
        $jadwal = $this->jadwalAktif($pemesanan);
        if (! $jadwal) {
            return redirect()->route('admin.orders.show', $pemesanan)->with('error', 'Belum ada jadwal interview aktif untuk pesanan ini.');
        }

        $link = $this->layanan->kirimUndangan($jadwal, $pihak, $request->user()->id_pengguna);
        if (! $link) {
            return redirect()->route('admin.orders.show', $pemesanan)->with('error', 'Nomor WhatsApp belum tersedia untuk ' . $jadwal->penerima($pihak)->nama . '.');
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'link' => $link]);
        }

        return redirect()->away($link);
    }

    /** "Tandai Hadir" / "Tandai Minta Ubah Jadwal" / "Tandai Tidak Merespons". */
    public function tandai(Request $request, Pemesanan $pemesanan, string $pihak)
    {
        $jadwal = $this->jadwalAktif($pemesanan);
        if (! $jadwal) {
            return back()->with('error', 'Belum ada jadwal interview aktif untuk pesanan ini.');
        }

        $kunciCatatan = 'catatan_' . $pihak;
        $data = $request->validate([
            'status' => ['required', Rule::in(['hadir', 'minta_ubah', 'tidak_merespons'])],
            $kunciCatatan => ['nullable', 'required_if:status,minta_ubah', 'string', 'max:500'],
        ], [
            $kunciCatatan . '.required_if' => 'Catatan/alasan wajib diisi saat menandai Minta Ubah Jadwal.',
            $kunciCatatan . '.max' => 'Catatan maksimal 500 karakter.',
        ]);

        $this->layanan->tandai($jadwal, $pihak, $data['status'], $data[$kunciCatatan] ?? null, $request->user()->id_pengguna);

        return redirect()->to(route('admin.orders.show', $pemesanan) . '#jadwal-interview')
            ->with('success', JadwalInterview::PIHAK[$pihak] . ' (' . $jadwal->penerima($pihak)->nama . ') ditandai "' . $jadwal->labelKonfirmasi($pihak) . '".');
    }

    /** Hasil interview "Cocok - Salurkan Pekerja". */
    public function cocok(Request $request, Pemesanan $pemesanan, PenyaluranService $penyaluran)
    {
        $jadwal = $this->jadwalAktif($pemesanan);
        if (! $jadwal) {
            return back()->with('error', 'Hasil interview hanya dapat dicatat untuk pesanan Disetujui yang memiliki jadwal interview.');
        }

        $data = $request->validate([
            'tanggal_mulai_kerja' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $jadwal->tanggal_interview->format('Y-m-d'), 'before_or_equal:' . now(JadwalInterview::ZONA)->addYear()->toDateString()],
            'catatan_cocok' => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal_mulai_kerja.required' => 'Tanggal mulai kerja wajib diisi.',
            'tanggal_mulai_kerja.date_format' => 'Format tanggal mulai kerja tidak valid.',
            'tanggal_mulai_kerja.after_or_equal' => 'Tanggal mulai kerja tidak boleh sebelum tanggal interview (' . $jadwal->tanggal_interview->locale('id')->translatedFormat('d F Y') . ').',
            'tanggal_mulai_kerja.before_or_equal' => 'Tanggal mulai kerja maksimal satu tahun dari hari ini.',
            'catatan_cocok.max' => 'Catatan maksimal 500 karakter.',
        ]);

        try {
            $hasil = $penyaluran->catatCocok($jadwal, $data['tanggal_mulai_kerja'], $data['catatan_cocok'] ?? null, $request->user()->id_pengguna);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $pesan = $jadwal->penerima('pekerja')->nama . ' tersalurkan ke ' . $jadwal->penerima('klien')->nama
            . ' mulai ' . $hasil['penyaluran']->labelTanggalMulai() . '. Pekerja tidak lagi tampil di katalog.';
        if ($hasil['dibatalkan']->isNotEmpty()) {
            $pesan .= ' ' . $hasil['dibatalkan']->count() . ' pesanan lain untuk pekerja ini dibatalkan otomatis.';
        }

        return redirect()->to(route('admin.orders.show', $pemesanan) . '#hasil-interview')->with('success', $pesan);
    }

    /** Hasil interview "Tidak Cocok". */
    public function tidakCocok(Request $request, Pemesanan $pemesanan, PenyaluranService $penyaluran)
    {
        $jadwal = $this->jadwalAktif($pemesanan);
        if (! $jadwal) {
            return back()->with('error', 'Hasil interview hanya dapat dicatat untuk pesanan Disetujui yang memiliki jadwal interview.');
        }

        $data = $request->validate([
            'alasan_tidak_cocok' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'alasan_tidak_cocok.required' => 'Alasan wajib diisi.',
            'alasan_tidak_cocok.min' => 'Alasan minimal 5 karakter.',
            'alasan_tidak_cocok.max' => 'Alasan maksimal 500 karakter.',
        ]);

        $penyaluran->catatTidakCocok($jadwal, $data['alasan_tidak_cocok'], $request->user()->id_pengguna);

        return redirect()->to(route('admin.orders.show', $pemesanan) . '#hasil-interview')
            ->with('success', 'Hasil interview "Tidak Cocok" dicatat. Pesanan ' . $pemesanan->kode() . ' dibatalkan dan pekerja tetap tampil di katalog.');
    }

    /* ---------- Bantuan ---------- */

    private function jadwalAktif(Pemesanan $pemesanan): ?JadwalInterview
    {
        $pemesanan->load(['klien', 'profilPekerja.pengguna', 'jadwalInterview']);
        $jadwal = $pemesanan->jadwalInterview;
        if ($pemesanan->status_pemesanan !== 'disetujui' || ! $jadwal) {
            return null;
        }

        return $jadwal->setRelation('pemesanan', $pemesanan);
    }

    /** Validasi form jadwal interview (modal Setujui maupun Ubah Jadwal). */
    private function validasiJadwal(Request $request): array
    {
        $hariIni = now(JadwalInterview::ZONA)->toDateString();

        $data = $request->validate([
            'tanggal_interview' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $hariIni, 'before_or_equal:' . now(JadwalInterview::ZONA)->addYear()->toDateString()],
            'waktu_interview' => ['required', 'date_format:H:i'],
            'metode' => ['required', Rule::in(array_keys(JadwalInterview::METODE))],
            'lokasi_atau_link' => ['required', 'string', 'max:255'],
            'catatan_interview' => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal_interview.required' => 'Tanggal interview wajib diisi.',
            'tanggal_interview.date_format' => 'Format tanggal interview tidak valid.',
            'tanggal_interview.after_or_equal' => 'Tanggal interview tidak boleh di masa lalu.',
            'tanggal_interview.before_or_equal' => 'Tanggal interview maksimal satu tahun dari hari ini.',
            'waktu_interview.required' => 'Waktu interview wajib diisi.',
            'waktu_interview.date_format' => 'Format waktu interview harus JJ:MM, mis. 09:30.',
            'metode.required' => 'Pilih metode interview.',
            'metode.in' => 'Metode interview harus Offline atau Online.',
            'lokasi_atau_link.required' => $request->input('metode') === 'online'
                ? 'Link meeting wajib diisi untuk interview online.'
                : 'Lokasi interview wajib diisi untuk interview offline.',
        ], [
            'lokasi_atau_link' => 'lokasi atau link meeting',
            'catatan_interview' => 'catatan',
        ]);

        // Aturan tambahan: link wajib URL untuk online, dan jam tidak boleh lewat bila tanggalnya hari ini
        $validator = validator($data, []);
        $validator->after(function (Validator $v) use ($data) {
            if ($data['metode'] === 'online') {
                $link = $data['lokasi_atau_link'];
                if (! filter_var($link, FILTER_VALIDATE_URL) || ! preg_match('#^https?://#i', $link)) {
                    $v->errors()->add('lokasi_atau_link', 'Link meeting harus berupa URL yang valid, diawali https:// (mis. https://meet.google.com/abc-defg-hij).');
                }
            }
            if (Carbon::parse($data['tanggal_interview'] . ' ' . $data['waktu_interview'], JadwalInterview::ZONA)->isPast()) {
                $v->errors()->add('waktu_interview', 'Waktu interview sudah lewat. Pilih jam setelah sekarang.');
            }
        });
        $validator->validate();

        return [
            'tanggal_interview' => $data['tanggal_interview'],
            'waktu_interview' => $data['waktu_interview'] . ':00',
            'metode' => $data['metode'],
            'lokasi_atau_link' => trim($data['lokasi_atau_link']),
            'catatan' => filled($data['catatan_interview'] ?? null) ? trim($data['catatan_interview']) : null,
        ];
    }
}
