<?php

namespace App\Providers;

use App\Console\Commands\NotifyContractExpiry;
use App\Models\WarehousePenerimaanBarangFarmasi;
use App\Models\WarehousePenerimaanBarangNonFarmasi;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Filesystem\Filesystem;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('files', function ($app) {
            return new Filesystem;
        });
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

        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        // if (!Session::has('app_type')) {
        //     Session::put('app_type', 'hr');
        // }

        // // Share session to all views
        // View::share('appType', session('app_type', 'hr'));

        Relation::morphMap([
            'penerimaan_farmasi' => WarehousePenerimaanBarangFarmasi::class,
            'penerimaan_non_farmasi' => WarehousePenerimaanBarangNonFarmasi::class,
        ]);
    }
}
