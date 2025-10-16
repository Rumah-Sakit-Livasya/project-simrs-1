<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            // tambahkan di sini
            \App\Http\Middleware\LastSeenUser::class,
            \App\Http\Middleware\TrackOnlineUser::class,
        ]);

        $middleware->alias([
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'check.api.credentials' => \App\Http\Middleware\CheckApiCredentials::class,
            'auth.api' => \App\Http\Middleware\AuthenticatedApi::class,
            'mjkn.auth' => \App\Http\Middleware\MjknAuthMiddleware::class,
        ]);
    })
    ->withProviders([
        // Daftarkan provider Anda yang lain di sini jika perlu,
        // tapi untuk kasus ini, kita hanya butuh BroadcastServiceProvider.
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
    ])
    // >>>>> PENAMBAHAN SELESAI DI SINI <<<<<
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) { // <-- TAMBAHKAN BAGIAN INI
        // Daftarkan semua jadwal Anda di sini
        $schedule->command('app:charge-daily-room-rates')->dailyAt('01:00');

        // Contoh lain jika ada
        // $schedule->command('backup:run')->daily();
    })->create();
