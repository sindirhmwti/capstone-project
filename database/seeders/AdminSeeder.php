<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Akun admin bawaan. Admin tidak memiliki halaman registrasi.
     */
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'admin@shakaabadi.com'],
            [
                'nama' => 'Admin Shaka',
                'no_telepon' => '081200000001',
                'kata_sandi' => 'Admin123!', // otomatis di-hash bcrypt melalui cast "hashed"
                'peran' => Pengguna::PERAN_ADMIN,
                'status_akun' => 'aktif',
            ]
        );
    }
}
