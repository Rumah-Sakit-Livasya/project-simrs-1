<?php

namespace Database\Seeders;

use App\Models\InternalVehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternalVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding internal vehicles...');

        // Hapus data lama jika ada, untuk menghindari duplikat no. plat saat seeding ulang
        // InternalVehicle::truncate(); // Opsional: gunakan jika ingin membersihkan tabel sebelum seeding

        // Membuat 15 data kendaraan menggunakan factory
        InternalVehicle::factory()->count(15)->create();
    }
}
