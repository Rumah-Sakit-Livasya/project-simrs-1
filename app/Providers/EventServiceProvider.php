<?php

namespace App\Providers;

use App\Events\BillingFinalized;
use App\Listeners\CreateKonfirmasiAsuransi;
use App\Models\RS\MaterialApproval;
use App\Models\WarehousePenerimaanBarangNonFarmasiItems;
use App\Observers\MaterialApprovalObserver;
use App\Observers\PenerimaanBarangObserver;
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

    protected $observers = [
        WarehousePenerimaanBarangNonFarmasiItems::class => [PenerimaanBarangObserver::class],
        MaterialApproval::class => [MaterialApprovalObserver::class], // <-- TAMBAHKAN INI
    ];
}
