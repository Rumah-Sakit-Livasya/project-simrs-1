<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisasi = [
            ['name' => 'Administrasi Umum'],
            ['name' => 'Bidang Pelayanan dan Penunjang'],
            ['name' => 'Direksi'],
            ['name' => 'Humas & Marketing'],
            ['name' => 'Pelayanan Medis'],
            ['name' => 'Penunjang Medis'],
            ['name' => 'Sub Bagian Keuangan'],
            ['name' => 'Sub Bagian SDM, Kesekretariatan, Humas & Marketing'],
            ['name' => 'Sub Bagian Umum'],
            ['name' => 'Unit Farmasi'],
            ['name' => 'Unit Gizi'],
            ['name' => 'Unit Humas'],
            ['name' => 'Unit IGD'],
            ['name' => 'Unit Intensif Care'],
            ['name' => 'Unit IT'],
            ['name' => 'Unit Kesekretariatan'],
            ['name' => 'Unit Keuangan'],
            ['name' => 'Unit Koder Casemix'],
            ['name' => 'Unit Laboratorium'],
            ['name' => 'Unit Logistik'],
            ['name' => 'Unit Marketing'],
            ['name' => 'Unit OK'],
            ['name' => 'Unit Perinatologi'],
            ['name' => 'Unit Radiologi'],
            ['name' => 'Unit Rawat Inap'],
            ['name' => 'Unit Rawat Inap 2'],
            ['name' => 'Unit Rawat Inap 3'],
            ['name' => 'Unit Rawat Jalan'],
            ['name' => 'Unit Rekam Medis'],
            ['name' => 'Unit Rumah Tangga'],
            ['name' => 'Unit SDM'],
            ['name' => 'Unit Umum'],
            ['name' => 'Unit VK & PONEK'],
            ['name' => 'PT'],
        ];

        foreach ($organisasi as $data) {
            Organization::create($data);
        }
    }
}
