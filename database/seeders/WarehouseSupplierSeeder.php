<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WarehouseSupplier; // Import model

class WarehouseSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi saat seeder dijalankan ulang
        // WarehouseSupplier::truncate();

        WarehouseSupplier::create([
            'nama' => 'PT. Kimia Farma Trading',
            'alamat' => 'Jl. Farmasi No. 1, Jakarta',
            'phone' => '021123456',
            'ppn' => 11.00,
            'aktif' => 1,
        ]);

        WarehouseSupplier::create([
            'nama' => 'PT. Indofarma Global Medica',
            'alamat' => 'Jl. Industri No. 2, Bandung',
            'phone' => '022123456',
            'ppn' => 0.00,
            'aktif' => 1,
        ]);
    }
}
