<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobPositions = [
            ['name' => 'Admin Laboratorium'],
            ['name' => 'Admin Pendaftaran'],
            ['name' => 'Analis Laboratorium'],
            ['name' => 'Apoteker'],
            ['name' => 'Asisten Apoteker'],
            ['name' => 'Asisten TTK'],
            ['name' => 'Bidan Pelaksana'],
            ['name' => 'Customer Service'],
            ['name' => 'Direktur'],
            ['name' => 'Dokter Spesialis Anak'],
            ['name' => 'Dokter Spesialis Anastesi'],
            ['name' => 'Dokter Spesialis Obstetri & Ginekologi (Obgyn)'],
            ['name' => 'Dokter Spesialis Patologi Klinik'],
            ['name' => 'Dokter Spesialis Bedah'],
            ['name' => 'Dokter Umum'],
            ['name' => 'Helper'],
            ['name' => 'Kasir Administrasi'],
            ['name' => 'Kepala'],
            ['name' => 'Kepala Bagian'],
            ['name' => 'Kepala Seksi'],
            ['name' => 'Kepala Sub Bagian'],
            ['name' => 'Koki'],
            ['name' => 'Owner'],
            ['name' => 'Pelaksana CSSD'],
            ['name' => 'Penanggung Jawab'],
            ['name' => 'Perawat Pelaksana'],
            ['name' => 'Petugas Laundry'],
            ['name' => 'Pramusaji'],
            ['name' => 'Radiografer'],
            ['name' => 'Security'],
            ['name' => 'Staf'],
            ['name' => 'Staf Koder Casemix'],
            ['name' => 'Teknisi PSRS'],
            ['name' => 'Driver'],
            ['name' => 'Management PT'],
            ['name' => 'KGH'],
            ['name' => 'Kantin'],
            ['name' => 'TPS'],
            ['name' => 'Helper'],
            ['name' => 'Portal']
        ];

        foreach ($jobPositions as $data) {
            JobPosition::create($data);
        }
    }
}
