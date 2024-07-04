<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kolonial
        // latitude = -6.763461746615957, 
        // longitude = 108.16947348181606

        // Rs Livaysa
        // latitude = -6.764976435287691 
        // longitude = 108.17786913965288
        DB::table('companies')->insert([
            'name' => 'Rumah Sakit Livasya',
            'phone_number' => '(0233) 8668019',
            'email' => 'contact@livasya.com',
            'address' => 'Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453',
            'province' => 'Jawa Barat',
            'city' => 'Majalengka',
            'logo' => 'logo.png',
            'category' => 'Kesehatan',
            'class' => 'Kelas C',
            'operating_permit_number' => '29032200430920001',
            'latitude' => -6.764976435287691,
            'longitude' => 108.17786913965288,
            'radius' => 1
        ]);
        DB::table('companies')->insert([
            'name' => 'PT Livasya Sudjono Bersaudara',
            'phone_number' => '(0233) 8668019',
            'email' => 'pt@gmail.com',
            'address' => 'Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453',
            'province' => 'Jawa Barat',
            'city' => 'Majalengka',
            'logo' => 'logo.png',
            'category' => 'PT',
            'class' => '-',
            'operating_permit_number' => '-',
            'latitude' => -6.764976435287691,
            'longitude' => 108.17786913965288,
            'radius' => 1
        ]);
        DB::table('companies')->insert([
            'name' => 'Kolonial Guest House',
            'phone_number' => '(0233) 8668019',
            'email' => 'kolonial@gmail.com',
            'address' => 'Jl. Brawijaya Desa No.01, RT.04/RW.08, Kadipaten, Kec. Kadipaten, Kabupaten Majalengka, Jawa Barat 45452',
            'province' => 'Jawa Barat',
            'city' => 'Majalengka',
            'logo' => 'logo.png',
            'category' => 'Penginapan',
            'class' => '-',
            'operating_permit_number' => '-',
            'latitude' => -6.763461746615957,
            'longitude' => 108.16947348181606,
            'radius' => 1
        ]);
    }
}
