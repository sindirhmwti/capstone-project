<?php

namespace Database\Seeders;

use App\Models\DokumenPekerja;
use App\Models\Kriteria;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AkunTestingSeeder extends Seeder
{
    /**
     * Akun contoh untuk pengujian (klien dan pekerja dengan kriteria bervariasi).
     */
    public function run(): void
    {
        $admin = Pengguna::where('email', 'admin@shakaabadi.com')->first();

        // ---------- Klien ----------
        $klien = Pengguna::updateOrCreate(['email' => 'klien@test.com'], [
            'nama' => 'Rani Amelia',
            'no_telepon' => '081200000002',
            'kata_sandi' => 'Klien123!',
            'peran' => Pengguna::PERAN_KLIEN,
            'status_akun' => 'aktif',
        ]);
        Notifikasi::kirim($klien->id_pengguna, 'Selamat datang di Temukerja', 'Akun klien Anda berhasil dibuat. Silakan cari pekerja yang sesuai kebutuhan Anda.');

        Pengguna::updateOrCreate(['email' => 'klien2@test.com'], [
            'nama' => 'Hendra Wijaya',
            'no_telepon' => '081200000003',
            'kata_sandi' => 'Klien123!',
            'peran' => Pengguna::PERAN_KLIEN,
            'status_akun' => 'aktif',
        ]);

        // ---------- Pekerja ----------
        // Kriteria berisi nama kriteria yang bernilai "ya"; selain itu bernilai "tidak".
        $daftarPekerja = [
            [
                'email' => 'art@test.com', 'nama' => 'Budi Santoso', 'kategori' => 'art',
                'status' => 'terverifikasi', 'alamat' => 'Jakarta Selatan', 'lahir' => '1990-04-12', 'jk' => 'Laki-laki',
                'pengalaman' => 5, 'keterampilan' => 'Memasak, Kebersihan rumah, Mencuci',
                'deskripsi' => 'Berpengalaman 5 tahun sebagai ART di rumah tangga dengan anak kecil.',
                'kriteria' => ['Nyaman dengan anjing', 'Nyaman dengan kucing', 'Tidak mudah mabuk darat', 'Tahan ruangan ber-AC', 'Bisa memasak', 'Bisa mengendarai motor', 'Bersedia bekerja di rumah bertingkat'],
            ],
            [
                'email' => 'babysitter@test.com', 'nama' => 'Andi Pratama', 'kategori' => 'baby_sitter',
                'status' => 'terverifikasi', 'alamat' => 'Jakarta Barat', 'lahir' => '1995-08-20', 'jk' => 'Laki-laki',
                'pengalaman' => 4, 'keterampilan' => 'Pengasuhan anak, Pendampingan belajar',
                'deskripsi' => 'Pengasuh anak bersertifikat pelatihan baby sitter, terbiasa mendampingi anak usia 1-6 tahun.',
                'kriteria' => ['Nyaman dengan kucing', 'Tahan ruangan ber-AC', 'Tidak memiliki alergi debu', 'Berpengalaman merawat bayi/anak', 'Bersedia menginap', 'Bersedia bekerja di akhir pekan'],
            ],
            [
                'email' => 'lansia@test.com', 'nama' => 'Dimas Saputra', 'kategori' => 'perawat_lansia',
                'status' => 'menunggu', 'alamat' => 'Jakarta Selatan', 'lahir' => '1992-01-05', 'jk' => 'Laki-laki',
                'pengalaman' => 7, 'keterampilan' => 'Pendampingan lansia, Aktivitas harian',
                'deskripsi' => 'Perawat lansia dengan pengalaman 7 tahun mendampingi lansia dengan keterbatasan gerak.',
                'kriteria' => ['Nyaman dengan anjing', 'Tidak mudah mabuk darat', 'Tahan ruangan ber-AC', 'Berpengalaman merawat lansia', 'Bersedia menginap'],
            ],
            [
                'email' => 'siti@test.com', 'nama' => 'Siti Rahayu', 'kategori' => 'art',
                'status' => 'terverifikasi', 'alamat' => 'Jakarta Selatan', 'lahir' => '1988-11-02', 'jk' => 'Perempuan',
                'pengalaman' => 8, 'keterampilan' => 'Memasak, Menyetrika, Kebersihan rumah',
                'deskripsi' => 'ART berpengalaman, terbiasa bekerja di rumah bertingkat dan menginap.',
                'kriteria' => ['Nyaman dengan kucing', 'Tahan ruangan ber-AC', 'Tidak memiliki alergi debu', 'Bisa memasak', 'Bersedia menginap', 'Bersedia bekerja di rumah bertingkat', 'Bersedia bekerja di akhir pekan'],
            ],
            [
                'email' => 'wati@test.com', 'nama' => 'Wati Susanti', 'kategori' => 'art',
                'status' => 'terverifikasi', 'alamat' => 'Tangerang', 'lahir' => '1993-03-17', 'jk' => 'Perempuan',
                'pengalaman' => 3, 'keterampilan' => 'Mencuci, Kebersihan rumah',
                'deskripsi' => 'ART yang rapi dan teliti. Memiliki alergi bulu kucing.',
                'kriteria' => ['Nyaman dengan anjing', 'Tidak mudah mabuk darat', 'Bisa mengendarai motor', 'Bersedia bekerja di akhir pekan'],
            ],
            [
                'email' => 'dewi@test.com', 'nama' => 'Dewi Lestari', 'kategori' => 'baby_sitter',
                'status' => 'terverifikasi', 'alamat' => 'Tangerang', 'lahir' => '1997-06-30', 'jk' => 'Perempuan',
                'pengalaman' => 2, 'keterampilan' => 'Pengasuhan bayi, Memasak MPASI',
                'deskripsi' => 'Baby sitter lulusan pelatihan resmi, terbiasa merawat bayi baru lahir.',
                'kriteria' => ['Nyaman dengan anjing', 'Nyaman dengan kucing', 'Tidak mudah mabuk darat', 'Tahan ruangan ber-AC', 'Bisa memasak', 'Berpengalaman merawat bayi/anak', 'Bersedia menginap'],
            ],
            [
                'email' => 'nurul@test.com', 'nama' => 'Nurul Aini', 'kategori' => 'perawat_lansia',
                'status' => 'terverifikasi', 'alamat' => 'Jakarta Timur', 'lahir' => '1985-09-09', 'jk' => 'Perempuan',
                'pengalaman' => 10, 'keterampilan' => 'Pendampingan lansia, Perawatan pasca stroke',
                'deskripsi' => 'Perawat lansia senior dengan pengalaman merawat lansia pasca stroke.',
                'kriteria' => ['Nyaman dengan kucing', 'Tahan ruangan ber-AC', 'Tidak memiliki alergi debu', 'Bisa memasak', 'Berpengalaman merawat lansia', 'Bersedia menginap', 'Bersedia bekerja di akhir pekan'],
            ],
            [
                'email' => 'laila@test.com', 'nama' => 'Laila Permata', 'kategori' => 'perawat_lansia',
                'status' => 'terverifikasi', 'alamat' => 'Tangerang', 'lahir' => '1991-12-21', 'jk' => 'Perempuan',
                'pengalaman' => 4, 'keterampilan' => 'Pendampingan lansia, Aktivitas harian',
                'deskripsi' => 'Mudah mabuk perjalanan sehingga lebih cocok untuk pekerjaan di rumah.',
                'kriteria' => ['Nyaman dengan anjing', 'Nyaman dengan kucing', 'Berpengalaman merawat lansia', 'Bersedia bekerja di rumah bertingkat'],
            ],
            [
                'email' => 'rina@test.com', 'nama' => 'Rina Marlina', 'kategori' => 'baby_sitter',
                'status' => 'ditolak', 'alamat' => 'Jakarta Utara', 'lahir' => '1999-02-14', 'jk' => 'Perempuan',
                'pengalaman' => 1, 'keterampilan' => 'Pengasuhan anak',
                'deskripsi' => 'Baby sitter pemula.',
                'kriteria' => ['Nyaman dengan kucing', 'Berpengalaman merawat bayi/anak'],
                'catatan' => 'Sertifikat pelatihan tidak terbaca. Silakan unggah ulang dengan foto yang lebih jelas.',
            ],
            [
                'email' => 'sri@test.com', 'nama' => 'Sri Wahyuni', 'kategori' => 'art',
                'status' => 'terverifikasi', 'alamat' => 'Jakarta Barat', 'lahir' => '1987-07-07', 'jk' => 'Perempuan',
                'pengalaman' => 6, 'keterampilan' => 'Memasak, Kebersihan rumah',
                'deskripsi' => 'Akun contoh yang dinonaktifkan admin (tidak tampil di katalog).',
                'kriteria' => ['Nyaman dengan anjing', 'Bisa memasak', 'Bersedia menginap'],
                'akun' => 'nonaktif',
            ],
        ];

        $kriteria = Kriteria::ambilSemua()->pluck('id_kriteria', 'nama_kriteria');

        foreach ($daftarPekerja as $data) {
            $pengguna = Pengguna::updateOrCreate(['email' => $data['email']], [
                'nama' => $data['nama'],
                'no_telepon' => '0813' . str_pad((string) crc32($data['email']) % 100000000, 8, '0', STR_PAD_LEFT),
                'kata_sandi' => 'Pekerja123!',
                'peran' => Pengguna::PERAN_PEKERJA,
                'status_akun' => $data['akun'] ?? 'aktif',
            ]);

            $sudahDiperiksa = in_array($data['status'], ['terverifikasi', 'ditolak'], true);

            $profil = ProfilPekerja::updateOrCreate(['id_pengguna' => $pengguna->id_pengguna], [
                'kategori_pekerjaan' => $data['kategori'],
                'tanggal_lahir' => $data['lahir'],
                'jenis_kelamin' => $data['jk'],
                'alamat' => $data['alamat'],
                'lama_pengalaman' => $data['pengalaman'],
                'keterampilan' => $data['keterampilan'],
                'deskripsi_profil' => $data['deskripsi'],
                'status_verifikasi' => $data['status'],
                'catatan_verifikasi' => $data['catatan'] ?? null,
                'diverifikasi_oleh' => $sudahDiperiksa ? $admin?->id_pengguna : null,
                'tanggal_verifikasi' => $sudahDiperiksa ? now()->subDays(10) : null,
            ]);

            // Nilai kriteria: ya untuk yang terdaftar, tidak untuk sisanya
            $nilai = [];
            foreach ($kriteria as $nama => $id) {
                $nilai[$id] = ['nilai_kriteria' => in_array($nama, $data['kriteria'], true) ? 'ya' : 'tidak'];
            }
            $profil->kriteria()->sync($nilai);

            // Dokumen contoh (berkas PDF sederhana di storage/app/private/dokumen)
            $statusDokumen = match ($data['status']) {
                'terverifikasi' => 'valid',
                'ditolak' => 'tidak_valid',
                default => 'menunggu',
            };
            $profil->dokumen()->delete();
            foreach ($profil->dokumenWajib() as $jenis) {
                $profil->dokumen()->create([
                    'jenis_dokumen' => $jenis,
                    'lokasi_berkas' => $this->buatBerkasContoh($profil->id_pekerja, $jenis, $data['nama']),
                    'status_verifikasi' => $jenis === 'ktp' && $data['status'] === 'ditolak' ? 'valid' : $statusDokumen,
                ]);
            }

            Notifikasi::kirim($pengguna->id_pengguna, 'Selamat datang di Temukerja', 'Lengkapi profil, kriteria khusus, dan dokumen Anda agar dapat diverifikasi admin.');
        }

        if ($admin) {
            Notifikasi::kirim($admin->id_pengguna, 'Data pekerja menunggu verifikasi', 'Dimas Saputra (Perawat Lansia) telah mengirim dokumen dan menunggu pemeriksaan.');
        }
    }

    /**
     * Membuat berkas PDF sederhana sebagai contoh dokumen hasil unggahan.
     */
    private function buatBerkasContoh(int $idPekerja, string $jenis, string $nama): string
    {
        $judul = $jenis === 'ktp' ? 'KTP' : 'Sertifikat Pelatihan';
        $teks = "Contoh {$judul} - {$nama}";
        $stream = "BT /F1 18 Tf 60 760 Td ({$teks}) Tj ET";

        $objek = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
            '<< /Length ' . strlen($stream) . " >>\nstream\n{$stream}\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offset = [];
        foreach ($objek as $i => $isi) {
            $offset[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n{$isi}\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objek) + 1) . "\n0000000000 65535 f \n";
        foreach ($offset as $o) {
            $pdf .= str_pad((string) $o, 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        $pdf .= "trailer\n<< /Size " . (count($objek) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        $path = "dokumen/{$idPekerja}/contoh-{$jenis}.pdf";
        Storage::disk(DokumenPekerja::DISK)->put($path, $pdf);

        return $path;
    }
}
