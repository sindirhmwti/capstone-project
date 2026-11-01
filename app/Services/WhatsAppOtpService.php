<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Pengiriman & pemeriksaan kode OTP lewat WhatsApp.
 *
 * Driver diatur lewat config/otp.php (OTP_DRIVER di .env):
 * - "demo" : tidak mengirim pesan apa pun, hanya mencatat ke log; kode yang benar = OTP_DEMO_CODE.
 *
 * Integrasi API WhatsApp asli (mis. Fonnte, Wablas, atau WhatsApp Cloud API) nanti dipasang
 * di method kirimViaApi(): buat kode acak, simpan hash-nya (cache/session) beserta waktu kedaluwarsa,
 * kirim lewat HTTP ke penyedia, lalu verifikasiKode() membandingkan dengan hash tersebut.
 */
class WhatsAppOtpService
{
    public function driver(): string
    {
        return (string) config('otp.driver', 'demo');
    }

    public function isDemo(): bool
    {
        return $this->driver() === 'demo';
    }

    /** Mengirim kode OTP ke nomor WhatsApp (format 628xxxxxxxxxx). */
    public function kirimKode(string $nomor): bool
    {
        if ($this->isDemo()) {
            Log::info('[OTP DEMO] Kode verifikasi untuk ' . self::samarkan($nomor) . ' tidak dikirim (mode demo). Gunakan kode ' . config('otp.demo_code') . '.');

            return true;
        }

        return $this->kirimViaApi($nomor);
    }

    /** Memeriksa kode OTP yang dimasukkan pengguna. */
    public function verifikasiKode(string $nomor, string $kode): bool
    {
        if ($this->isDemo()) {
            return hash_equals((string) config('otp.demo_code'), $kode);
        }

        // Tempat pemeriksaan kode dari penyedia API asli
        return false;
    }

    /**
     * Tempat integrasi API WhatsApp asli (belum diimplementasikan).
     * Contoh: Http::withToken(env('FONNTE_TOKEN'))->post('https://api.fonnte.com/send', [...]).
     */
    protected function kirimViaApi(string $nomor): bool
    {
        Log::warning('[OTP] Driver "' . $this->driver() . '" belum diimplementasikan; kode tidak dikirim ke ' . self::samarkan($nomor) . '.');

        return false;
    }

    /** Menyeragamkan nomor Indonesia menjadi format 628xxxxxxxxxx. */
    public static function normalisasi(?string $nomor): string
    {
        $angka = preg_replace('/\D/', '', (string) $nomor);
        if (str_starts_with($angka, '0')) {
            $angka = '62' . substr($angka, 1);
        }

        return $angka;
    }

    /** Variasi penulisan nomor yang sama (untuk cek nomor sudah dipakai, termasuk data lama berformat 08...). */
    public static function variasi(string $nomor628): array
    {
        $lokal = '0' . substr($nomor628, 2);

        return [$nomor628, '+' . $nomor628, $lokal];
    }

    /** Menyamarkan nomor untuk ditampilkan, mis. 0812****6789. */
    public static function samarkan(string $nomor628): string
    {
        $lokal = str_starts_with($nomor628, '62') ? '0' . substr($nomor628, 2) : $nomor628;

        return substr($lokal, 0, 4) . str_repeat('*', max(4, strlen($lokal) - 8)) . substr($lokal, -4);
    }
}
