<?php

namespace Database\Seeders;

use App\Models\TimeSchedule;
use Illuminate\Database\Seeder;

class TimeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeSchedule::factory()->count(1)->create(); // Menghasilkan 10 data TimeSchedule
    }
}
