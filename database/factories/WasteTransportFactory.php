<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\WasteCategory;
use App\Models\WasteTransport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WasteTransport>
 */
class WasteTransportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil ID acak dari tabel relasi.
        // Pastikan Anda sudah menjalankan seeder untuk WasteCategory dan Vehicle.
        $categoryIds = WasteCategory::pluck('id');
        $vehicleIds = Vehicle::pluck('id');

        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'waste_category_id' => $this->faker->randomElement($categoryIds),
            'vehicle_id' => $this->faker->randomElement($vehicleIds),
            'volume' => $this->faker->randomFloat(2, 100.0, 1500.0), // Volume pengangkutan biasanya lebih besar
            'pic' => $this->faker->name(), // Nama PIC vendor acak
        ];
    }
}
