<?php

namespace Database\Factories;

use App\Models\TimeScheduleEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeScheduleEmployeeFactory extends Factory
{
    protected $model = TimeScheduleEmployee::class;

    public function definition()
    {
        return [
            'time_schedule_id' => \App\Models\TimeSchedule::inRandomOrder()->value('id'), // Mengambil time_schedule_id yang sudah ada
            'employee_id' => \App\Models\Employee::inRandomOrder()->value('id'), // Mengambil employee_id yang sudah ada
            'status' => $this->faker->randomElement(['hadir', null]),
        ];
    }
}
