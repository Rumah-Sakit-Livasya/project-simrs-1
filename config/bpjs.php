<?php

return [
    'cons_id'      => env('BPJS_CONS_ID'),
    'secret_key'   => env('BPJS_SECRET_KEY'),
    'user_key'     => env('BPJS_USER_KEY'), // <-- TAMBAHKAN INI
    'kode_ppk'     => env('BPJS_KODE_PPK'),

    'applicare' => [
        'base_url' => env('BPJS_APPLICARE_BASE_URL'),
    ],
];
