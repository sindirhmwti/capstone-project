<?php

/*
|--------------------------------------------------------------------------
| Verifikasi kode OTP WhatsApp saat registrasi
|--------------------------------------------------------------------------
| driver "demo": tidak ada pesan WhatsApp yang dikirim, kode selalu OTP_DEMO_CODE.
| Driver lain (mis. fonnte, wablas, cloud_api) disiapkan untuk integrasi API asli nanti.
*/

return [
    'driver' => env('OTP_DRIVER', 'demo'),

    // Kode tetap untuk mode demo
    'demo_code' => env('OTP_DEMO_CODE', '0000'),

    // Panjang kode OTP (jumlah digit)
    'panjang' => 4,

    // Data registrasi sementara di session kedaluwarsa setelah (menit)
    'kedaluwarsa_menit' => 10,

    // Maksimal percobaan salah sebelum dikunci sementara
    'maks_percobaan' => 5,

    // Lama kunci setelah terlalu banyak percobaan (detik)
    'lama_kunci_detik' => 60,

    // Jeda minimal sebelum boleh kirim ulang kode (detik)
    'jeda_kirim_ulang_detik' => 60,
];
