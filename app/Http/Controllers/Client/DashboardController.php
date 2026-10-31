<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProfilPekerja;
use App\Services\RekomendasiPekerja;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Beranda klien: ringkasan aktivitas dan rekomendasi pekerja. */
    public function index(Request $request, RekomendasiPekerja $rekomendasi)
    {
        $pengguna = $request->user();

        $stats = [
            'favorites' => $pengguna->favorit()->count(),
            'active_orders' => $pengguna->pemesanan()->whereIn('status_pemesanan', ['menunggu', 'diproses', 'disetujui'])->count(),
            'history' => $pengguna->pemesanan()->count(),
            'notifications' => $pengguna->jumlahNotifikasiBelumDibaca(),
        ];

        // Dasar rekomendasi: kriteria pencarian terakhir, atau kriteria pada pesanan terakhir
        $idKriteria = session('kriteria_dicari', []);
        if (! $idKriteria) {
            $pesananTerakhir = $pengguna->pemesanan()->with('kriteria')->latest('tanggal_pemesanan')->first();
            $idKriteria = $pesananTerakhir?->kriteria->pluck('id_kriteria')->all() ?? [];
        }

        $kandidat = ProfilPekerja::tampilDiKatalog()->with(['pengguna', 'kriteria'])->get();
        $rekomendasiPekerja = $idKriteria
            ? $rekomendasi->rekomendasikan($idKriteria, $kandidat)->take(3)
            : $kandidat->sortByDesc('lama_pengalaman')->take(3)->values()->map(fn ($p) => ['profil' => $p, 'skor' => null]);

        $pesananTerbaru = $pengguna->pemesanan()->with('profilPekerja.pengguna')->latest('tanggal_pemesanan')->take(3)->get();

        return view('client.dashboard', [
            'pengguna' => $pengguna,
            'stats' => $stats,
            'rekomendasiPekerja' => $rekomendasiPekerja,
            'berdasarkanKriteria' => (bool) $idKriteria,
            'idFavorit' => $pengguna->favorit()->pluck('id_pekerja')->all(),
            'pesananTerbaru' => $pesananTerbaru,
        ]);
    }
}
