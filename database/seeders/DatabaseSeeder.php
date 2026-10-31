<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi basis data dengan data awal.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            KriteriaSeeder::class,
            AkunTestingSeeder::class,
            PemesananContohSeeder::class,
        ]);
    }
}
