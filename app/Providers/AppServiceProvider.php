<?php

namespace App\Providers;

use App\Console\Commands\NotifyContractExpiry;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            // Cek apakah query adalah INSERT, UPDATE, atau DELETE, tetapi bukan untuk tabel 'sessions'
            if (preg_match('/^(insert|update|delete)/i', $query->sql) && !preg_match('/\bsessions\b/i', $query->sql)) {
                Log::channel('query_action')->debug('Query executed', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . ' ms',
                    'user' => Auth::check() ? Auth::user()->name : 'Guest',
                ]);
            }
        });

        // if (!Session::has('app_type')) {
        //     Session::put('app_type', 'hr');
        // }

        // // Share session to all views
        // View::share('appType', session('app_type', 'hr'));
    }
}
