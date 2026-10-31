<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Kriteria khusus hasil wawancara dengan PT Shaka Abadi Nusantara
     * (hewan peliharaan, mabuk darat, suhu AC, perawatan khusus, dll.).
     * Urutan id menentukan urutan dimensi vektor pada perhitungan Cosine Similarity.
     */
    public function run(): void
    {
        $daftar = [
            ['Nyaman dengan anjing', 'lingkungan', 'Tidak memiliki fobia atau alergi terhadap anjing.'],
            ['Nyaman dengan kucing', 'lingkungan', 'Tidak memiliki fobia atau alergi terhadap kucing.'],
            ['Tidak mudah mabuk darat', 'kondisi_fisik', 'Sanggup ikut bepergian dengan mobil tanpa mabuk perjalanan.'],
            ['Tahan ruangan ber-AC', 'kondisi_fisik', 'Sanggup bekerja di ruangan ber-AC dingin dalam waktu lama.'],
            ['Tidak memiliki alergi debu', 'kondisi_fisik', 'Tidak memiliki alergi debu sehingga nyaman membersihkan rumah.'],
            ['Bisa memasak', 'keahlian', 'Mampu memasak makanan rumahan sehari-hari.'],
            ['Berpengalaman merawat bayi/anak', 'keahlian', 'Memiliki pengalaman mengasuh bayi atau anak.'],
            ['Berpengalaman merawat lansia', 'keahlian', 'Memiliki pengalaman mendampingi dan merawat lansia.'],
            ['Bisa mengendarai motor', 'keahlian', 'Mampu mengendarai sepeda motor untuk keperluan rumah tangga.'],
            ['Bersedia menginap', 'ketersediaan', 'Bersedia tinggal (menginap) di rumah klien.'],
            ['Bersedia bekerja di rumah bertingkat', 'lingkungan', 'Sanggup bekerja di rumah dengan lebih dari satu lantai.'],
            ['Bersedia bekerja di akhir pekan', 'ketersediaan', 'Bersedia bekerja pada hari Sabtu dan Minggu.'],
        ];

        foreach ($daftar as [$nama, $kategori, $deskripsi]) {
            Kriteria::updateOrCreate(
                ['nama_kriteria' => $nama],
                ['kategori_kriteria' => $kategori, 'deskripsi' => $deskripsi]
            );
        }
    }
}
