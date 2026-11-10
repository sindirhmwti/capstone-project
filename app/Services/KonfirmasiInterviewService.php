<?php

namespace App\Services;

use App\Models\JadwalInterview;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

/**
 * Pencatatan undangan & jawaban interview oleh admin (setelah berkomunikasi lewat WhatsApp).
 * Setiap pencatatan disimpan beserta waktunya, mengirim notifikasi website, dan masuk ke
 * riwayat_status_pemesanan sebagai catatan (tanpa menambah nilai status baru).
 */
class KonfirmasiInterviewService
{
    public function __construct(private WhatsAppService $whatsapp)
    {
    }

    /** Admin mengirim undangan: catat waktunya, panggil kirimPesan() (demo: log), dan kembalikan link wa.me. */
    public function kirimUndangan(JadwalInterview $jadwal, string $pihak, int $idAdmin): ?string
    {
        $penerima = $jadwal->penerima($pihak);
        $pesan = $jadwal->pesanUndangan($pihak);
        $link = $jadwal->linkUndangan($pihak);
        if (! $link) {
            return null;
        }

        $ulang = $jadwal->undanganDikirimPada($pihak) !== null;

        DB::transaction(function () use ($jadwal, $pihak, $idAdmin, $penerima, $ulang) {
            $jadwal->{'undangan_' . $pihak . '_dikirim_pada'} = now();
            $jadwal->save();

            $this->catatRiwayat($jadwal, $idAdmin, ($jadwal->pernahDiubah() ? 'Undangan perubahan jadwal interview' : 'Undangan interview')
                . ($ulang ? ' dikirim ulang' : ' dikirim') . ' via WhatsApp ke ' . mb_strtolower(JadwalInterview::PIHAK[$pihak]) . ' (' . $penerima->nama . ').');
        });

        $this->whatsapp->kirimPesan($penerima->no_telepon, $pesan);

        return $link;
    }

    /**
     * Admin menandai jawaban pihak: hadir, minta_ubah (dengan catatan), atau tidak_merespons.
     */
    public function tandai(JadwalInterview $jadwal, string $pihak, string $status, ?string $catatan, int $idAdmin): void
    {
        $penerima = $jadwal->penerima($pihak);
        $label = JadwalInterview::PIHAK[$pihak];
        $catatan = filled($catatan) ? trim($catatan) : null;

        DB::transaction(function () use ($jadwal, $pihak, $status, $catatan, $idAdmin, $penerima, $label) {
            $jadwal->{'konfirmasi_' . $pihak} = $status;
            $jadwal->{'catatan_konfirmasi_' . $pihak} = $catatan;
            $jadwal->{'dikonfirmasi_' . $pihak . '_pada'} = now();
            $jadwal->save();

            $kode = $jadwal->pemesanan->kode();
            $waktu = $jadwal->labelTanggal() . ', ' . $jadwal->labelWaktu();
            $teksCatatan = $catatan ? ' Catatan: ' . rtrim($catatan, '.') . '.' : '';

            [$judul, $isi, $riwayat] = match ($status) {
                'hadir' => [
                    'Kehadiran interview Anda telah dikonfirmasi admin',
                    'Kehadiran Anda pada interview pesanan ' . $kode . ' (' . $waktu . ') telah dikonfirmasi admin.',
                    $label . ' (' . $penerima->nama . ') dikonfirmasi HADIR interview (dicatat admin dari WhatsApp).',
                ],
                'minta_ubah' => [
                    'Permintaan ubah jadwal interview sedang ditinjau',
                    'Permintaan ubah jadwal interview pesanan ' . $kode . ' telah dicatat dan sedang ditinjau admin.' . $teksCatatan,
                    $label . ' (' . $penerima->nama . ') meminta UBAH JADWAL interview (dicatat admin dari WhatsApp).' . $teksCatatan,
                ],
                default => [
                    'Mohon balas undangan interview',
                    'Admin belum menerima balasan Anda untuk undangan interview pesanan ' . $kode . ' (' . $waktu . '). Mohon segera membalas pesan WhatsApp dari admin.',
                    $label . ' (' . $penerima->nama . ') ditandai TIDAK MERESPONS undangan interview.' . $teksCatatan,
                ],
            };

            Notifikasi::kirim($penerima->id_pengguna, $judul, $isi);
            $this->catatRiwayat($jadwal, $idAdmin, $riwayat);
        });
    }

    /** Catatan di riwayat status pesanan tanpa menambah nilai status baru. */
    public function catatRiwayat(JadwalInterview $jadwal, int $idPengguna, string $catatan): void
    {
        $jadwal->pemesanan->riwayatStatus()->create([
            'diubah_oleh' => $idPengguna,
            'status_sebelumnya' => null,
            'status_baru' => $jadwal->pemesanan->status_pemesanan,
            'catatan_perubahan' => $catatan,
        ]);
    }
}
