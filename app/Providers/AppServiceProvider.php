<?php

namespace App\Providers;

use App\Console\Commands\NotifyContractExpiry;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            Log::channel('query_action')->debug('Query executed', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time . ' ms',
            ]);
        });
    }
}
