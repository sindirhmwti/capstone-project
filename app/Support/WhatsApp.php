<?php

namespace App\Support;

/**
 * Bantuan nomor & link WhatsApp (https://wa.me/62xxxxxxxxxx?text=...).
 * Nomor dinormalisasi ke format 62, teks di-urlencode (baris baru menjadi %0A sehingga tetap tampil berbaris).
 */
final class WhatsApp
{
    /** Menyeragamkan nomor Indonesia (08..., 62..., +62...) menjadi format 628xxxxxxxxxx. */
    public static function normalisasi(?string $nomor): string
    {
        $angka = preg_replace('/\D/', '', (string) $nomor);
        if (str_starts_with($angka, '0')) {
            $angka = '62' . substr($angka, 1);
        }

        return $angka;
    }

    /** Nomor valid bila berformat 628 diikuti 7-12 angka. */
    public static function valid(?string $nomor): bool
    {
        return (bool) preg_match('/^628[0-9]{7,12}$/', self::normalisasi($nomor));
    }

    /** Format tampilan, mis. 0812-3456-6789 (atau "-" bila kosong). */
    public static function tampil(?string $nomor): string
    {
        $n = self::normalisasi($nomor);
        if ($n === '') {
            return '-';
        }
        $lokal = str_starts_with($n, '62') ? '0' . substr($n, 2) : $n;

        return trim(chunk_split($lokal, 4, '-'), '-');
    }

    /** Menyamarkan nomor untuk ditampilkan, mis. 0812****6789 (atau "-" bila kosong). */
    public static function samarkan(?string $nomor): string
    {
        $n = self::normalisasi($nomor);
        if ($n === '') {
            return '-';
        }
        $lokal = str_starts_with($n, '62') ? '0' . substr($n, 2) : $n;

        return substr($lokal, 0, 4) . str_repeat('*', max(4, strlen($lokal) - 8)) . substr($lokal, -4);
    }

    /** Link https://wa.me/62xxx?text=... ; null bila nomor kosong / tidak valid. */
    public static function link(?string $nomor, string $pesan = ''): ?string
    {
        if (! self::valid($nomor)) {
            return null;
        }

        return 'https://wa.me/' . self::normalisasi($nomor) . ($pesan !== '' ? '?text=' . rawurlencode($pesan) : '');
    }
}
