<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Notifikasi;
use App\Models\ProfilPekerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VerifikasiController extends Controller
{
    /** Daftar pekerja yang memerlukan pemeriksaan data (default: menunggu verifikasi). */
    public function index(Request $request)
    {
        $status = $request->query('status', 'menunggu');

        $pekerja = ProfilPekerja::with(['pengguna', 'dokumen'])
            ->when($status !== 'semua', fn ($q) => $q->where('status_verifikasi', $status))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_pekerjaan', $request->kategori))
            ->orderBy('id_pekerja')
            ->paginate(15)->withQueryString();

        $jumlah = ProfilPekerja::selectRaw('status_verifikasi, count(*) as jumlah')
            ->groupBy('status_verifikasi')->pluck('jumlah', 'status_verifikasi');

        return view('admin.verification.index', compact('pekerja', 'status', 'jumlah'));
    }

    /** Halaman pemeriksaan data dan dokumen pekerja. */
    public function show(ProfilPekerja $pekerja)
    {
        $pekerja->load(['pengguna', 'dokumen', 'kriteria', 'verifikator']);

        return view('admin.verification.show', [
            'profil' => $pekerja,
            'semuaKriteria' => Kriteria::ambilSemua(),
            'nilaiKriteria' => $pekerja->ambilKriteria(),
        ]);
    }

    /**
     * Menetapkan hasil verifikasi:
     * - setujui  : status terverifikasi, seluruh dokumen valid
     * - perbaikan: status belum_lengkap (dokumen tidak lengkap / perlu diperbaiki)
     * - tolak    : status ditolak
     */
    public function proses(Request $request, ProfilPekerja $pekerja)
    {
        $pekerja->load(['pengguna', 'dokumen']);

        if ($pekerja->status_verifikasi !== 'menunggu') {
            return back()->with('error', 'Hanya data berstatus "Menunggu Verifikasi" yang dapat diperiksa.');
        }

        $data = $request->validate([
            'keputusan' => ['required', Rule::in(['setujui', 'perbaikan', 'tolak'])],
            'catatan' => ['nullable', 'required_unless:keputusan,setujui', 'string', 'max:1000'],
            'dokumen' => ['nullable', 'array'],
            'dokumen.*' => [Rule::in(['valid', 'tidak_valid'])],
        ], [
            'catatan.required_unless' => 'Catatan pemeriksaan wajib diisi bila data diminta perbaikan atau ditolak.',
        ]);

        if ($data['keputusan'] === 'setujui' && ! $pekerja->cekKelengkapanDokumen()) {
            return back()->with('error', 'Dokumen wajib belum lengkap sehingga data tidak dapat disetujui. Pilih "Minta Perbaikan".');
        }

        $statusBaru = ['setujui' => 'terverifikasi', 'perbaikan' => 'belum_lengkap', 'tolak' => 'ditolak'][$data['keputusan']];

        DB::transaction(function () use ($pekerja, $data, $statusBaru, $request) {
            foreach ($pekerja->dokumen as $dokumen) {
                $status = $data['keputusan'] === 'setujui'
                    ? 'valid'
                    : ($data['dokumen'][$dokumen->id_dokumen] ?? 'tidak_valid');
                $dokumen->ubahStatusVerifikasi($status);
            }

            $pekerja->ubahStatusVerifikasi($statusBaru, $request->user()->id_pengguna, $data['catatan'] ?? null);
        });

        $pesan = [
            'terverifikasi' => ['Verifikasi disetujui', 'Selamat! Data dan dokumen Anda telah diverifikasi. Profil Anda kini tampil di katalog klien.'],
            'belum_lengkap' => ['Data perlu diperbaiki', 'Admin meminta perbaikan data/dokumen Anda. Catatan: ' . ($data['catatan'] ?? '-')],
            'ditolak' => ['Verifikasi ditolak', 'Verifikasi data Anda ditolak. Catatan: ' . ($data['catatan'] ?? '-')],
        ][$statusBaru];
        Notifikasi::kirim($pekerja->id_pengguna, $pesan[0], $pesan[1]);

        return redirect()->route('admin.verification')
            ->with('success', 'Hasil verifikasi ' . $pekerja->pengguna->nama . ' disimpan: ' . ProfilPekerja::STATUS_VERIFIKASI[$statusBaru] . '.');
    }
}
