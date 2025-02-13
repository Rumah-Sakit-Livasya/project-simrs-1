<?php

namespace Database\Factories;

use App\Models\TimeSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeScheduleFactory extends Factory
{
    protected $model = TimeSchedule::class;

    public function definition()
    {
        return [
            'employee_id' => \App\Models\Employee::inRandomOrder()->value('id'), // Mengambil employee_id yang sudah ada
            'title' => $this->faker->sentence,
            'perihal' => $this->faker->text,
            'type' => $this->faker->randomElement(['rapat', 'kegiatan']),
            'datetime' => $this->faker->dateTime,
            'undangan' => $this->faker->word,
            'materi' => $this->faker->word,
            'absensi' => $this->faker->word,
            'notulen' => $this->faker->word,
            'is_online' => $this->faker->boolean,
            'room_name' => $this->faker->word,
            'link' => $this->faker->url,
        ];
    }
}
