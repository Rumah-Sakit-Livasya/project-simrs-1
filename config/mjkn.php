<?php
return [
    'cons_id' => env('BPJS_CONS_ID'),
    'secret_key' => env('BPJS_SECRET_KEY'),
    'user_key' => env('BPJS_USER_KEY'),
    'kode_ppk' => env('BPJS_KODE_PPK'),
    'ws_username' => env('MJKN_WS_USERNAME'),
    'ws_password' => env('MJKN_WS_PASSWORD'),
    'token_validity_minutes' => 15, // Token berlaku selama 15 menit
];