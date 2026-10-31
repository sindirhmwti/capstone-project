<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     * TODO: Fetch real stats from Supabase PostgreSQL.
     */
    public function index()
    {
        // Dummy stats — replace with Supabase queries later
        $stats = [
            'total_workers'   => 128,
            'pending_verify'  => 14,
            'total_clients'   => 243,
            'active_orders'   => 37,
        ];

        $recentActivities = [
            ['name' => 'Dewi Lestari',   'category' => 'Baby Sitter',    'status' => 'Menunggu',      'date' => '2026-10-30'],
            ['name' => 'Nurul Aini',     'category' => 'Perawat Lansia', 'status' => 'Terverifikasi', 'date' => '2026-10-29'],
            ['name' => 'Siti Rahayu',    'category' => 'ART',            'status' => 'Aktif',         'date' => '2026-10-29'],
            ['name' => 'Rina Marlina',   'category' => 'Baby Sitter',    'status' => 'Menunggu',      'date' => '2026-10-28'],
            ['name' => 'Laila Permata',  'category' => 'Perawat Lansia', 'status' => 'Terverifikasi', 'date' => '2026-10-27'],
        ];

        $pendingVerifications = [
            ['name' => 'Dewi Lestari', 'category' => 'Baby Sitter',    'date' => '2026-10-30'],
            ['name' => 'Rina Marlina', 'category' => 'Baby Sitter',    'date' => '2026-10-28'],
            ['name' => 'Laila Permata','category' => 'Perawat Lansia', 'date' => '2026-10-27'],
        ];

        return view('admin.dashboard', compact('stats', 'recentActivities', 'pendingVerifications'));
    }
}
