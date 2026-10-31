<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Pemesanan;
use App\Models\Pengguna;
use App\Models\RiwayatStatusPemesanan;
use Illuminate\Database\Seeder;

class PemesananContohSeeder extends Seeder
{
    /**
     * Pesanan contoh agar riwayat pesanan, kelola pemesanan, dan laporan penyaluran dapat diuji.
     */
    public function run(): void
    {
        $admin = Pengguna::where('email', 'admin@shakaabadi.com')->first();
        $klien = Pengguna::where('email', 'klien@test.com')->first();
        $klien2 = Pengguna::where('email', 'klien2@test.com')->first();
        $kriteria = Kriteria::ambilSemua()->pluck('id_kriteria', 'nama_kriteria');

        $daftar = [
            // [klien, email pekerja, hari lalu, alur status, kriteria diminta, catatan]
            [$klien, 'siti@test.com', 60, ['disetujui', 'selesai'], ['Bisa memasak', 'Bersedia menginap'], 'Butuh ART untuk rumah 2 lantai, ada 1 anak sekolah.'],
            [$klien2, 'nurul@test.com', 35, ['diproses', 'disetujui', 'selesai'], ['Berpengalaman merawat lansia'], 'Merawat ibu saya pasca stroke.'],
            [$klien2, 'dewi@test.com', 12, ['disetujui'], ['Berpengalaman merawat bayi/anak', 'Nyaman dengan anjing'], 'Ada anjing peliharaan di rumah.'],
            [$klien, 'art@test.com', 2, [], ['Nyaman dengan kucing', 'Bisa memasak', 'Bisa mengendarai motor'], 'Saya membutuhkan ART yang bisa membantu mengurus rumah 3 lantai, ada hewan peliharaan kucing.'],
        ];

        foreach ($daftar as [$pemesan, $emailPekerja, $hariLalu, $alur, $kriteriaDiminta, $catatan]) {
            $profil = Pengguna::where('email', $emailPekerja)->first()?->profilPekerja;
            if (! $pemesan || ! $profil) {
                continue;
            }

            $pesanan = Pemesanan::create([
                'id_klien' => $pemesan->id_pengguna,
                'id_pekerja' => $profil->id_pekerja,
                'status_pemesanan' => 'menunggu',
                'tanggal_mulai' => now()->subDays($hariLalu)->addDays(7)->toDateString(),
                'lokasi_kerja' => $profil->alamat,
                'catatan_klien' => $catatan,
            ]);
            $pesanan->tanggal_pemesanan = now()->subDays($hariLalu);
            $pesanan->save();

            $pesanan->kriteria()->sync(
                collect($kriteriaDiminta)->mapWithKeys(fn ($nama) => [$kriteria[$nama] => ['nilai_yang_diminta' => 'ya']])->all()
            );

            $pesanan->riwayatStatus()->create([
                'diubah_oleh' => $pemesan->id_pengguna,
                'status_sebelumnya' => null,
                'status_baru' => 'menunggu',
                'catatan_perubahan' => 'Pesanan diajukan oleh klien.',
                'tanggal_perubahan' => now()->subDays($hariLalu),
            ]);

            foreach ($alur as $i => $status) {
                $pesanan->ubahStatus($status, $admin?->id_pengguna, 'Diperbarui oleh admin.');
                RiwayatStatusPemesanan::where('id_pemesanan', $pesanan->id_pemesanan)
                    ->latest('id_riwayat')->first()
                    ?->forceFill(['tanggal_perubahan' => now()->subDays($hariLalu)->addDays($i + 1)])->save();
            }
        }
    }
}
