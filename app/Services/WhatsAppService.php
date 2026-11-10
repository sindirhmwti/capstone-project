<?php

namespace App\Services;

use App\Support\WhatsApp;
use Illuminate\Support\Facades\Log;

/**
 * Pengiriman pesan WhatsApp untuk undangan interview.
 *
 * Saat ini belum ada API WhatsApp berbayar, sehingga pesan benar-benar dikirim admin lewat link wa.me
 * (lihat link()). kirimPesan() dipanggil setiap undangan dikirim dan, sesuai driver
 * (WHATSAPP_DRIVER, bawaan mengikuti OTP_DRIVER):
 * - "demo" : hanya mencatat isi pesan ke log Laravel (storage/logs/laravel.log).
 * - lainnya: tempat integrasi API asli (mis. Fonnte) nanti, tanpa mengubah alur di controller.
 */
class WhatsAppService
{
    public function driver(): string
    {
        return (string) config('services.whatsapp.driver', 'demo');
    }

    public function isDemo(): bool
    {
        return $this->driver() === 'demo';
    }

    /** Link wa.me dengan nomor tujuan dan isi pesan terisi otomatis (null bila nomor tidak valid). */
    public function link(?string $nomor, string $pesan): ?string
    {
        return WhatsApp::link($nomor, $pesan);
    }

    /** Mengirim pesan WhatsApp. Mode demo: dicatat ke log. Mengembalikan true bila tercatat/terkirim. */
    public function kirimPesan(?string $nomor, string $pesan): bool
    {
        $tujuan = WhatsApp::normalisasi($nomor);

        if ($this->isDemo()) {
            Log::info('[WHATSAPP DEMO] Pesan ke ' . ($tujuan ?: '(nomor kosong)') . " (dikirim manual lewat wa.me):\n" . $pesan);

            return true;
        }

        return $this->kirimViaApi($tujuan, $pesan);
    }

    /**
     * Tempat integrasi API WhatsApp asli (belum diimplementasikan).
     * Contoh Fonnte: Http::withHeaders(['Authorization' => env('FONNTE_TOKEN')])
     *     ->post('https://api.fonnte.com/send', ['target' => $tujuan, 'message' => $pesan])->successful();
     */
    protected function kirimViaApi(string $tujuan, string $pesan): bool
    {
        Log::warning('[WHATSAPP] Driver "' . $this->driver() . '" belum diimplementasikan; pesan ke ' . $tujuan . ' tidak dikirim otomatis.');

        return false;
    }
}
