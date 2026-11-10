<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // WhatsApp untuk undangan & konfirmasi interview
    'whatsapp' => [
        // Nomor admin/kantor (format 62xxxxxxxxxx) tujuan tombol "Konfirmasi via WhatsApp"
        'admin' => env('ADMIN_WHATSAPP'),
        // Driver kirimPesan(): "demo" = catat ke log. Disiapkan untuk API asli (mis. Fonnte) nanti.
        'driver' => env('WHATSAPP_DRIVER', env('OTP_DRIVER', 'demo')),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
