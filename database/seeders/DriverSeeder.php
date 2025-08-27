<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding drivers...');

        // Cek jika ada employee, jika tidak, buat 10
        if (Employee::count() == 0) {
            $this->command->warn('No employees found, creating 10 new employees...');
            Employee::factory()->count(10)->create();
        }

        // Ambil 5 employee pertama untuk dijadikan driver
        $employeesToBeDrivers = Employee::take(5)->get();

        foreach ($employeesToBeDrivers as $employee) {
            // Gunakan firstOrCreate untuk menghindari duplikat jika seeder dijalankan lagi
            Driver::firstOrCreate(
                ['employee_id' => $employee->id], // Kondisi pengecekan
                [ // Data yang akan diisi jika belum ada
                    'no_sim' => fake()->numerify('############'), // 12 digit nomor SIM
                    'masa_berlaku_sim' => fake()->dateTimeBetween('+1 year', '+5 years')
                ]
            );
        }
    }
}
