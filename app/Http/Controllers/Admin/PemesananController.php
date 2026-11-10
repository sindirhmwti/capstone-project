<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemesananController extends Controller
{
    /** Daftar pemesanan dari klien. */
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $pesanan = Pemesanan::with(['klien', 'profilPekerja.pengguna', 'jadwalInterview'])
            ->when($status !== 'semua', fn ($q) => $q->where('status_pemesanan', $status))
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->cari;
                $q->where(fn ($w) => $w->whereHas('klien', fn ($k) => $k->where('nama', 'like', "%{$cari}%"))
                    ->orWhereHas('profilPekerja.pengguna', fn ($p) => $p->where('nama', 'like', "%{$cari}%")));
            })
            ->latest('tanggal_pemesanan')
            ->paginate(15)->withQueryString();

        $jumlah = Pemesanan::selectRaw('status_pemesanan, count(*) as jumlah')
            ->groupBy('status_pemesanan')->pluck('jumlah', 'status_pemesanan');

        return view('admin.orders.index', compact('pesanan', 'status', 'jumlah'));
    }

    /** Detail pemesanan dan informasi pekerja terkait. */
    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['klien', 'profilPekerja.pengguna', 'profilPekerja.kriteria', 'kriteria', 'riwayatStatus.pengubah', 'pembatal', 'jadwalInterview.pencatatHasil', 'penyaluran']);
        $pemesanan->jadwalInterview?->setRelation('pemesanan', $pemesanan);

        return view('admin.orders.show', [
            'pemesanan' => $pemesanan,
            'nilaiKriteria' => $pemesanan->profilPekerja->ambilKriteria(),
        ]);
    }

    /** Memperbarui status pemesanan, mencatat riwayat, dan mengirim notifikasi. */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $pemesanan->load(['klien', 'profilPekerja.pengguna']);
        $diizinkan = $pemesanan->statusBerikutnyaAdmin();

        if (! $diizinkan) {
            return back()->with('error', 'Pesanan berstatus "' . $pemesanan->labelStatus() . '" sudah final dan tidak dapat diubah.');
        }

        $data = $request->validate([
            'status_pemesanan' => ['required', Rule::in($diizinkan)],
            'catatan' => ['nullable', 'required_if:status_pemesanan,ditolak,dibatalkan', 'string', 'max:500'],
        ], [
            'status_pemesanan.in' => 'Perubahan status dari "' . $pemesanan->labelStatus() . '" ke status tersebut tidak diizinkan.',
            'catatan.required_if' => 'Alasan wajib diisi bila pesanan ditolak atau dibatalkan.',
        ]);

        // Pesanan disetujui diselesaikan lewat panel "Hasil Interview" agar data penyaluran terbentuk
        if ($data['status_pemesanan'] === 'selesai' && $pemesanan->status_pemesanan === 'disetujui') {
            return back()->with('error', 'Gunakan tombol "Cocok - Salurkan Pekerja" pada bagian Hasil Interview untuk menyelesaikan pesanan ini.');
        }

        // Persetujuan kini wajib disertai jadwal interview (tombol "Setujui" → Admin\InterviewController::setujui)
        if ($data['status_pemesanan'] === 'disetujui') {
            return back()->with('error', 'Gunakan tombol "Setujui & Jadwalkan Interview" agar jadwal tersimpan dan undangan email terkirim.');
        }

        $pemesanan->ubahStatus($data['status_pemesanan'], $request->user()->id_pengguna, $data['catatan'] ?? null);

        $label = $pemesanan->labelStatus();
        $kode = $pemesanan->kode();
        $catatan = filled($data['catatan'] ?? null) ? ' Catatan: ' . $data['catatan'] : '';

        Notifikasi::kirim($pemesanan->id_klien, 'Status pemesanan Anda diperbarui',
            'Pesanan ' . $kode . ' untuk ' . $pemesanan->profilPekerja->pengguna->nama . ' kini berstatus "' . $label . '".' . $catatan);

        if (in_array($data['status_pemesanan'], ['disetujui', 'selesai', 'dibatalkan'], true)) {
            $isi = [
                'disetujui' => 'Anda dijadwalkan bekerja untuk klien ' . $pemesanan->klien->nama . ' mulai ' . $pemesanan->tanggal_mulai?->translatedFormat('d F Y') . ' di ' . $pemesanan->lokasi_kerja . '. Admin akan menghubungi Anda.',
                'selesai' => 'Penempatan pada pesanan ' . $kode . ' telah dinyatakan selesai.',
                'dibatalkan' => 'Penempatan pada pesanan ' . $kode . ' dibatalkan oleh admin.' . $catatan,
            ][$data['status_pemesanan']];
            Notifikasi::kirim($pemesanan->profilPekerja->id_pengguna, 'Informasi penempatan ' . $kode, $isi);
        }

        return back()->with('success', 'Status pesanan ' . $kode . ' diperbarui menjadi "' . $label . '".');
    }
}
