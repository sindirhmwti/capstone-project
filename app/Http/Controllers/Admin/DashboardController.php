<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;

class DashboardController extends Controller
{
    /** Dashboard admin: ringkasan pekerja, verifikasi, dan pemesanan. */
    public function index()
    {
        $stats = [
            'total_workers' => ProfilPekerja::count(),
            'verified_workers' => ProfilPekerja::tampilDiKatalog()->count(),
            'pending_verify' => ProfilPekerja::where('status_verifikasi', 'menunggu')->count(),
            'total_clients' => Pengguna::where('peran', Pengguna::PERAN_KLIEN)->count(),
            'incoming_orders' => Pemesanan::whereIn('status_pemesanan', ['menunggu', 'diproses'])->count(),
            'placements_month' => Pemesanan::whereIn('status_pemesanan', Pemesanan::STATUS_PENYALURAN)
                ->whereMonth('tanggal_pemesanan', now()->month)->whereYear('tanggal_pemesanan', now()->year)->count(),
        ];

        $perKategori = ProfilPekerja::selectRaw('kategori_pekerjaan, count(*) as jumlah')
            ->groupBy('kategori_pekerjaan')->pluck('jumlah', 'kategori_pekerjaan');

        $pendingVerifications = ProfilPekerja::with('pengguna')
            ->where('status_verifikasi', 'menunggu')
            ->orderBy('id_pekerja')->take(5)->get();

        $recentOrders = Pemesanan::with(['klien', 'profilPekerja.pengguna'])
            ->latest('tanggal_pemesanan')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'perKategori', 'pendingVerifications', 'recentOrders'));
    }
}
