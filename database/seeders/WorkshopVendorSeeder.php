<?php

namespace Database\Seeders;

use App\Models\WorkshopVendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkshopVendorSeeder extends Seeder
{
    public function run(): void
    {
        WorkshopVendor::factory()->count(20)->create();
    }
}
