<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalVehicle>
 */
class InternalVehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['KR4', 'KR2']);
        $brand = ($type === 'KR4')
            ? fake()->randomElement(['Toyota Avanza', 'Suzuki Ertiga', 'Daihatsu Xenia', 'Mitsubishi Xpander', 'Honda Mobilio'])
            : fake()->randomElement(['Honda Beat', 'Yamaha NMAX', 'Honda Vario', 'Suzuki Address']);

        return [
            'name' => $type . ' ' . Str::of($brand)->explode(' ')[1] . ' ' . fake()->colorName(),
            'type' => $type,
            'license_plate' => Str::upper(fake()->randomLetter()) . ' ' . fake()->numberBetween(1000, 9999) . ' ' . Str::upper(fake()->lexify('??')),
            'brand_model' => $brand,
            'model_year' => fake()->numberBetween(2015, 2023),
            'tax_due_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
            'stnk_due_date' => fake()->dateTimeBetween('+1 year', '+5 years'),
            'service_schedule_km' => fake()->randomElement([5000, 10000]),
            'service_schedule_months' => fake()->randomElement([6, 12]),
            'current_km' => $currentKm = fake()->numberBetween(10000, 150000),
            'last_oil_change_km' => $currentKm - fake()->numberBetween(1000, 10000),
        ];
    }
}
