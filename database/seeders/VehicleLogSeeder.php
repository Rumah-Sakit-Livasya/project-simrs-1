<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\InternalVehicle;
use App\Models\VehicleLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VehicleLogSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding vehicle logs...');

        $vehicles = InternalVehicle::all();
        $drivers = Driver::all();

        if ($vehicles->isEmpty() || $drivers->isEmpty()) {
            $this->command->error('Cannot seed Vehicle Logs. Please seed Internal Vehicles and Drivers first.');
            return;
        }

        // Hapus log lama untuk menghindari data aneh saat seeding ulang
        VehicleLog::truncate();

        $usedVehicleIds = [];

        // 1. Buat 3 log yang statusnya masih "Digunakan"
        for ($i = 0; $i < 3; $i++) {
            // Ambil kendaraan yang belum dipakai di log "Digunakan"
            $vehicle = $vehicles->whereNotIn('id', $usedVehicleIds)->random();
            if (!$vehicle) break; // Berhenti jika semua kendaraan sudah terpakai

            $usedVehicleIds[] = $vehicle->id;
            $driver = $drivers->random();

            VehicleLog::create([
                'internal_vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'start_datetime' => fake()->dateTimeBetween('-1 day', 'now'),
                'start_odometer' => fake()->numberBetween(10000, 50000),
                'destination' => fake()->city(),
                'status' => 'Digunakan'
            ]);
        }


        // 2. Buat 30 log yang statusnya sudah "Selesai"
        for ($i = 0; $i < 30; $i++) {
            $vehicle = $vehicles->random();
            $driver = $drivers->random();

            $start_datetime = Carbon::instance(fake()->dateTimeBetween('-3 months', '-2 days'));
            $end_datetime = (clone $start_datetime)->addHours(fake()->numberBetween(1, 48));
            $start_odometer = fake()->numberBetween(10000, 50000);
            $end_odometer = $start_odometer + fake()->numberBetween(50, 500);

            VehicleLog::create([
                'internal_vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'start_datetime' => $start_datetime,
                'start_odometer' => $start_odometer,
                'destination' => fake()->city(),
                'end_datetime' => $end_datetime,
                'end_odometer' => $end_odometer,
                'return_notes' => fake()->optional(0.3)->sentence(), // 30% kemungkinan ada catatan
                'status' => 'Selesai'
            ]);
        }
    }
}
