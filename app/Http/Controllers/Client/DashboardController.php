<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the client dashboard.
     * TODO: Fetch real data from Supabase PostgreSQL.
     */
    public function index()
    {
        // Dummy session data — replace with Supabase Auth session
        $user = [
            'name'   => 'Budi Santoso',
            'email'  => 'budi@example.com',
            'avatar' => null,
        ];

        $stats = [
            'favorites'     => 5,
            'active_orders' => 2,
            'history'       => 8,
            'notifications' => 3,
        ];

        // Dummy recommended workers
        $recommendedWorkers = [
            ['name' => 'Siti Rahayu',   'category' => 'ART',            'rating' => 4.8, 'location' => 'Jakarta Selatan', 'avatar' => null],
            ['name' => 'Dewi Lestari',  'category' => 'Baby Sitter',    'rating' => 4.9, 'location' => 'Bandung',         'avatar' => null],
            ['name' => 'Nurul Aini',    'category' => 'Perawat Lansia', 'rating' => 4.7, 'location' => 'Surabaya',        'avatar' => null],
            ['name' => 'Rina Marlina',  'category' => 'Baby Sitter',    'rating' => 4.6, 'location' => 'Jakarta Barat',   'avatar' => null],
        ];

        return view('client.dashboard', compact('user', 'stats', 'recommendedWorkers'));
    }
}

