<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /** Halaman Laporan Penyaluran dengan filter periode. */
    public function index(Request $request)
    {
        return view('admin.reports.index', $this->dataLaporan($request));
    }

    /** Versi siap cetak (print / simpan sebagai PDF lewat browser). */
    public function cetak(Request $request)
    {
        return view('admin.reports.print', $this->dataLaporan($request));
    }

    /**
     * Penyaluran = pemesanan berstatus "selesai" (pekerja tersalurkan setelah hasil interview cocok).
     * Periode berupa bulan (YYYY-MM) berdasarkan tanggal mulai kerja; kosong berarti semua periode.
     */
    private function dataLaporan(Request $request): array
    {
        $request->validate([
            'periode' => ['nullable', 'date_format:Y-m'],
            'kategori' => ['nullable', 'in:art,baby_sitter,perawat_lansia'],
        ], [
            'periode.date_format' => 'Format periode laporan tidak valid.',
        ]);

        $periode = $request->query('periode');
        $awal = $periode ? Carbon::createFromFormat('Y-m', $periode)->startOfMonth() : null;

        $penyaluran = Pemesanan::daftarPenyaluran($awal, $request->filled('kategori') ? $request->kategori : null);

        return [
            'penyaluran' => $penyaluran,
            'periode' => $periode,
            'labelPeriode' => $awal ? $awal->translatedFormat('F Y') : 'Semua periode',
            'perKategori' => $penyaluran->groupBy(fn ($o) => $o->profilPekerja->kategori_pekerjaan)->map->count(),
            // Status masa kerja: aktif (masih bekerja) / berakhir; data lama tanpa data penyaluran dihitung "berakhir"
            'perStatus' => $penyaluran->groupBy(fn ($o) => $o->penyaluran?->status ?? 'berakhir')->map->count(),
        ];
    }
}
