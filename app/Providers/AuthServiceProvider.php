<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\SIMRS\CPPT\CPPT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// TAMBAHKAN USE STATEMENTS INI
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ==========================================================
        // DEFINISIKAN GATE ANDA DI SINI
        // ==========================================================

        // Gate untuk memeriksa apakah pengguna boleh memodifikasi (edit/hapus) CPPT
        Gate::define('modify-cppt', function (User $user, CPPT $cppt) {
            return $user->id === $cppt->user_id;
        });

        // Gate untuk verifikasi
        Gate::define('verify-cppt', function (User $user, CPPT $cppt) {
            // Logika kustom bisa ditambahkan di sini
            return true;
        });
    }
}
