<?php
// app/Console/Commands/SendVehicleAlerts.php

namespace App\Console\Commands;

use App\Models\Driver;
use App\Models\InternalVehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendVehicleAlerts extends Command
{
    protected $signature = 'app:send-vehicle-alerts';
    protected $description = 'Cek dan kirim notifikasi untuk pajak, STNK, SIM, dan jadwal servis yang akan berakhir.';

    public function handle()
    {
        $this->info('Mulai pengecekan notifikasi kendaraan...');
        Log::info('Scheduled task [SendVehicleAlerts] started.');

        // 1. Cek Pajak Tahunan (H-30)
        $expiringTaxes = InternalVehicle::where('tax_due_date', '<=', now()->addDays(30))
            ->where('tax_due_date', '>=', now())
            ->get();
        foreach ($expiringTaxes as $vehicle) {
            $message = "Pajak Tahunan untuk {$vehicle->name} ({$vehicle->plate_number}) akan berakhir pada {$vehicle->tax_due_date}.";
            Log::warning($message); // Ganti ini dengan logika kirim notifikasi (email, dll)
            // TODO: Buat record di tabel notifikasi
        }

        // 2. Cek Pajak 5 Tahunan / STNK (H-60)
        $expiringStnk = InternalVehicle::where('stnk_due_date', '<=', now()->addDays(60))
            ->where('stnk_due_date', '>=', now())
            ->get();
        foreach ($expiringStnk as $vehicle) {
            $message = "STNK untuk {$vehicle->name} ({$vehicle->plate_number}) akan berakhir pada {$vehicle->stnk_due_date}.";
            Log::warning($message);
            // TODO: Buat record di tabel notifikasi
        }

        // 3. Cek SIM Pengemudi (H-30)
        $expiringLicenses = Driver::where('license_expiry_date', '<=', now()->addDays(30))
            ->where('license_expiry_date', '>=', now())
            ->get();
        foreach ($expiringLicenses as $driver) {
            $message = "SIM untuk pengemudi {$driver->name} akan berakhir pada {$driver->license_expiry_date}.";
            Log::warning($message);
            // TODO: Buat record di tabel notifikasi
        }

        // 4. Cek Jadwal Servis Berkala & Oli (Contoh)
        // Ini memerlukan penambahan kolom 'next_service_km', 'last_oil_change_km', dll di tabel internal_vehicles
        // $vehiclesNeedingService = InternalVehicle::whereRaw('current_km >= next_service_km')->get();
        // $vehiclesNeedingOilChange = InternalVehicle::whereRaw('current_km - last_oil_change_km >= 5000')->get();

        Log::info('Scheduled task [SendVehicleAlerts] finished.');
        $this->info('Pengecekan notifikasi selesai.');
        return 0;
    }
}
