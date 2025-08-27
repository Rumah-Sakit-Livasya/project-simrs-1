<?php

namespace Database\Seeders;

use App\Models\InspectionItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding master inspection items...');

        $items = [
            ['name' => 'Hidupkan Mesin', 'description' => 'Memastikan mesin dapat menyala dengan normal.'],
            ['name' => 'Tekanan Ban', 'description' => 'Memeriksa tekanan angin pada semua ban.'],
            ['name' => 'Penerangan & Kelistrikan', 'description' => 'Memeriksa fungsi lampu utama, sein, rem, dan kelistrikan lainnya.'],
            ['name' => 'Klakson', 'description' => 'Memastikan klakson berbunyi dengan nyaring.'],
            ['name' => 'Wiper', 'description' => 'Memeriksa fungsi wiper dan air wiper.'],
            ['name' => 'Accu / Aki', 'description' => 'Memeriksa kondisi visual dan daya aki.'],
            ['name' => 'Radiator', 'description' => 'Memeriksa level air radiator.'],
            ['name' => 'Bahan Bakar', 'description' => 'Memeriksa indikator bahan bakar.'],
            ['name' => 'Kebersihan', 'description' => 'Memeriksa kebersihan interior dan eksterior kendaraan.'],
            ['name' => 'Oli Mesin', 'description' => 'Memeriksa level oli mesin melalui dipstick.'],
        ];

        foreach ($items as $item) {
            InspectionItem::firstOrCreate(
                ['name' => $item['name']], // Kunci untuk memeriksa duplikat
                ['description' => $item['description'], 'is_active' => true] // Data tambahan jika record baru dibuat
            );
        }
    }
}
