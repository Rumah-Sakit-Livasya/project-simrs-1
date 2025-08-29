<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkshopVendorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Bengkel ' . fake()->company(),
            'address' => fake()->address(),
            'contact_person' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'specialization' => fake()->randomElement(['Umum', 'AC Mobil', 'Mesin Diesel', 'Body Repair', 'Kaki-kaki']),
        ];
    }
}
