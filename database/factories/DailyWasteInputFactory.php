<?php

namespace Database\Factories;

use App\Models\DailyWasteInput;
use App\Models\Employee;
use App\Models\WasteCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyWasteInput>
 */
class DailyWasteInputFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil ID acak dari tabel relasi yang sudah ada.
        // Ini jauh lebih efisien daripada query di dalam return array.
        // Pastikan Anda sudah menjalankan seeder untuk WasteCategory dan Employee terlebih dahulu.
        $categoryIds = WasteCategory::pluck('id');
        $picIds = Employee::pluck('id');

        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'waste_category_id' => $this->faker->randomElement($categoryIds),
            'volume' => $this->faker->randomFloat(2, 1.5, 75.0), // Volume antara 1.50 Kg hingga 75.00 Kg
            'pic' => $this->faker->randomElement($picIds),
        ];
    }
}
