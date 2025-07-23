<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk debugging

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('whatsapp-chat', function ($user) {
    // Anda bisa menambahkan logika lebih lanjut di sini,
    // misalnya, return $user->role === 'admin';
    return Auth::check();
});
