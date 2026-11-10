<?php

namespace App\Services;

use App\Models\JadwalInterview;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use App\Models\Penyaluran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Hasil interview & penyaluran pekerja.
 * - Cocok      : pesanan "selesai", penyaluran aktif (pekerja hilang dari katalog), pesanan lain pekerja itu dibatalkan.
 * - Tidak cocok: pesanan "dibatalkan" dengan alasan, pekerja tetap di katalog.
 * - Akhiri     : penyaluran "berakhir", pekerja otomatis tampil kembali di katalog.
 * Status pesanan memakai nilai yang sudah ada dan Pemesanan::ubahStatus() agar riwayat tercatat.
 */
class PenyaluranService
{
    public const ALASAN_TERSALURKAN = 'Pekerja telah tersalurkan ke klien lain';

    /**
     * @return array{penyaluran: Penyaluran, dibatalkan: \Illuminate\Support\Collection<int, Pemesanan>}
     */
    public function catatCocok(JadwalInterview $jadwal, string $tanggalMulai, ?string $catatan, int $idAdmin): array
    {
        $pemesanan = $jadwal->pemesanan;
        $profil = $pemesanan->profilPekerja;
        $catatan = filled($catatan) ? trim($catatan) : null;

        if ($profil->penyaluran()->where('status', 'aktif')->exists()) {
            throw new RuntimeException('Pekerja ini masih memiliki penyaluran aktif. Akhiri penyaluran tersebut terlebih dahulu.');
        }

        return DB::transaction(function () use ($jadwal, $pemesanan, $profil, $tanggalMulai, $catatan, $idAdmin) {
            $jadwal->forceFill([
                'hasil_interview' => 'cocok',
                'catatan_hasil' => $catatan,
                'hasil_dicatat_pada' => now(),
                'hasil_dicatat_oleh' => $idAdmin,
            ])->save();

            $pemesanan->ubahStatus('selesai', $idAdmin, 'Hasil interview cocok, pekerja tersalurkan' . ($catatan ? '. Catatan: ' . $catatan : '.'));

            $penyaluran = Penyaluran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'id_pekerja' => $profil->id_pekerja,
                'id_klien' => $pemesanan->id_klien,
                'tanggal_mulai' => $tanggalMulai,
                'status' => 'aktif',
                'catatan' => $catatan,
                'dibuat_oleh' => $idAdmin,
            ]);
            $pemesanan->setRelation('penyaluran', $penyaluran);

            // Pesanan lain yang masih aktif untuk pekerja yang sama otomatis dibatalkan
            $lainnya = Pemesanan::with('klien')
                ->where('id_pekerja', $profil->id_pekerja)
                ->whereKeyNot($pemesanan->getKey())
                ->whereIn('status_pemesanan', ['menunggu', 'diproses', 'disetujui'])
                ->get();
            foreach ($lainnya as $lain) {
                $lain->ubahStatus('dibatalkan', $idAdmin, self::ALASAN_TERSALURKAN);
                Notifikasi::kirim($lain->id_klien, 'Pesanan dibatalkan otomatis',
                    'Pesanan ' . $lain->kode() . ' untuk ' . $profil->pengguna->nama . ' dibatalkan karena pekerja telah tersalurkan ke klien lain. Silakan pilih pekerja lain di katalog.');
            }

            $mulai = Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d F Y');
            Notifikasi::kirim($pemesanan->id_klien, 'Penyaluran berhasil!',
                'Penyaluran berhasil! ' . $profil->pengguna->nama . ' akan mulai bekerja pada ' . $mulai . '. (Pesanan ' . $pemesanan->kode() . ')');
            Notifikasi::kirim($profil->id_pengguna, 'Selamat! Anda tersalurkan',
                'Selamat! Anda tersalurkan ke klien dan mulai bekerja pada ' . $mulai . '. Profil Anda tidak ditampilkan di katalog selama masa kerja.');

            return ['penyaluran' => $penyaluran, 'dibatalkan' => $lainnya];
        });
    }

    public function catatTidakCocok(JadwalInterview $jadwal, string $alasan, int $idAdmin): void
    {
        $pemesanan = $jadwal->pemesanan;
        $alasan = trim($alasan);

        DB::transaction(function () use ($jadwal, $pemesanan, $alasan, $idAdmin) {
            $jadwal->forceFill([
                'hasil_interview' => 'tidak_cocok',
                'catatan_hasil' => $alasan,
                'hasil_dicatat_pada' => now(),
                'hasil_dicatat_oleh' => $idAdmin,
            ])->save();

            // alasan_pembatalan, tanggal_pembatalan, dan dibatalkan_oleh diisi oleh ubahStatus()
            $pemesanan->ubahStatus('dibatalkan', $idAdmin, 'Hasil interview tidak cocok: ' . $alasan);

            Notifikasi::kirim($pemesanan->id_klien, 'Hasil interview belum cocok',
                'Hasil interview belum cocok. Silakan pilih pekerja lain di katalog. (Pesanan ' . $pemesanan->kode() . ')');
            Notifikasi::kirim($pemesanan->profilPekerja->id_pengguna, 'Hasil interview belum cocok',
                'Hasil interview belum cocok dengan klien. Profil Anda tetap tampil di katalog.');
        });
    }

    public function akhiri(Penyaluran $penyaluran, string $tanggalBerakhir, ?string $catatan, int $idAdmin): void
    {
        $penyaluran->loadMissing(['pemesanan', 'profilPekerja.pengguna', 'klien']);
        $catatan = filled($catatan) ? trim($catatan) : null;

        DB::transaction(function () use ($penyaluran, $tanggalBerakhir, $catatan, $idAdmin) {
            $penyaluran->forceFill([
                'status' => 'berakhir',
                'tanggal_berakhir' => $tanggalBerakhir,
                'catatan' => trim(implode("\n", array_filter([$penyaluran->catatan, $catatan ? 'Diakhiri: ' . $catatan : null]))) ?: null,
            ])->save();

            $selesai = Carbon::parse($tanggalBerakhir)->locale('id')->translatedFormat('d F Y');

            // Catatan di riwayat pesanan (tanpa status baru)
            $penyaluran->pemesanan->riwayatStatus()->create([
                'diubah_oleh' => $idAdmin,
                'status_sebelumnya' => null,
                'status_baru' => $penyaluran->pemesanan->status_pemesanan,
                'catatan_perubahan' => 'Penyaluran diakhiri pada ' . $selesai . ($catatan ? '. Catatan: ' . $catatan : '.'),
            ]);

            Notifikasi::kirim($penyaluran->profilPekerja->id_pengguna, 'Masa kerja berakhir',
                'Masa kerja Anda telah berakhir. Profil Anda kembali tampil di katalog.');
            Notifikasi::kirim($penyaluran->id_klien, 'Penyaluran berakhir',
                'Masa kerja ' . $penyaluran->profilPekerja->pengguna->nama . ' pada pesanan ' . $penyaluran->pemesanan->kode() . ' berakhir per ' . $selesai . '.');
        });
    }
}
