<?php

return [
    'cons_id'      => env('BPJS_CONS_ID'),
    'secret_key'   => env('BPJS_SECRET_KEY'),
    'kode_ppk'     => env('BPJS_KODE_PPK'),

    'applicare' => [
        'base_url' => env('BPJS_APPLICARE_BASE_URL'),
    ],

    // Anda bisa menambahkan konfigurasi VClaim, Antrean, dll di sini nanti
];
