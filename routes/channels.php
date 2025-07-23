<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

// Hapus komentar pada baris ini dan sesuaikan.
// Ini mengizinkan semua user yang sudah login untuk mendengarkan channel 'whatsapp-chat'.
Broadcast::channel('whatsapp-chat', function ($user) {
    // Anda bisa menambahkan logika lebih lanjut di sini,
    // misalnya, return $user->role === 'admin';
    return Auth::check();
});
