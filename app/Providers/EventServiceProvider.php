<?php

namespace App\Providers;

use App\Events\BillingFinalized;
use App\Listeners\CreateKonfirmasiAsuransi;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        BillingFinalized::class => [
            CreateKonfirmasiAsuransi::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
