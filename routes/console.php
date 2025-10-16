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

Schedule::command('notify:contract-expiry')
    ->mondays() // Menjalankan setiap hari Senin
    ->at('08:00'); // Pada pukul 08:00

Schedule::command('notify:document-expiry')
    ->mondays() // Menjalankan setiap hari Senin
    ->at('08:00'); // Pada pukul 08:00
