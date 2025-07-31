<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Memberitahu Laravel untuk menggunakan otentikasi dari file routes/channels.php
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
