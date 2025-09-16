<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeoLocation;

class GeoLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeoLocation::updateOrCreate(
            [
                // Kolom unik untuk mencari data
                'name' => 'RS Umum Livasya',
            ],
            [
                // Data yang akan dimasukkan atau diperbarui
                'province' => 'JAWA BARAT',
                'city' => 'KAB. MAJALENGKA',
                'address' => 'Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453',
                'longitude' => '108.1778155',
                'latitude' => '-6.7651256',
                // Baris untuk google_maps_api_key telah dihapus
            ]
        );

        // Jika Anda memiliki data lokasi lain, Anda bisa menambahkannya di sini
        // dengan pola yang sama.
    }
}
