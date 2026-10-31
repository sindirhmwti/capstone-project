<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the worker dashboard.
     * TODO: Fetch real data from Supabase PostgreSQL.
     */
    public function index()
    {
        // Dummy session data — replace with Supabase Auth session
        $user = [
            'name'     => 'Dewi Lestari',
            'email'    => 'dewi@example.com',
            'category' => 'Baby Sitter', // ART | Baby Sitter | Perawat Lansia
            'avatar'   => null,
        ];

        $stats = [
            'account_status'      => 'Aktif',
            'verification_status' => 'Menunggu Verifikasi', // Terverifikasi | Menunggu Verifikasi
            'documents_uploaded'  => 3,
            'notifications'       => 2,
        ];

        // Profile completion percentage (dummy)
        $profileCompletion = 70;

        $profileSections = [
            ['label' => 'Foto Profil',        'done' => true],
            ['label' => 'Data Pribadi',        'done' => true],
            ['label' => 'Pengalaman Kerja',    'done' => true],
            ['label' => 'Dokumen KTP',         'done' => false],
            ['label' => 'Sertifikat Pelatihan','done' => false],
        ];

        return view('worker.dashboard', compact('user', 'stats', 'profileCompletion', 'profileSections'));
    }
}

