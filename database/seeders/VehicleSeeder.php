<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::create(['plate_number' => 'B 1234 XYZ', 'vendor_name' => 'Vendor A']);
        Vehicle::create(['plate_number' => 'D 5678 ABC', 'vendor_name' => 'Vendor B']);
    }
}
