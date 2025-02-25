<?php

namespace Database\Seeders;

use App\Models\TimeScheduleEmployee;
use Illuminate\Database\Seeder;

class TimeScheduleEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeScheduleEmployee::factory()->count(21)->create(); // Menghasilkan 10 data TimeScheduleEmployee
    }
}
