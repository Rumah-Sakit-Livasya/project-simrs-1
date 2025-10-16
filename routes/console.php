<?php

use App\Console\Commands\NotifyContractExpiry;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schedule as ScheduleCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

ScheduleCommand::command('backup:clean')->daily()->at('00:00');
ScheduleCommand::command('backup:run')->daily()->at('00:30');

// --- Bagian untuk Command:notify-contract-expiry ---
ScheduleCommand::call(function () {
    // Jalankan command Artisan di dalam closure
    \Artisan::call('notify:contract-expiry');
})->mondays()->at('08:00');


// --- Bagian untuk Command:notify-document-expiry ---
ScheduleCommand::call(function () {
    // Jalankan command Artisan di dalam closure
    \Artisan::call('notify:document-expiry');
})->mondays()->at('08:00');
