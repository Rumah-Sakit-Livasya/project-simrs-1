<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKegiatan;

class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        JenisKegiatan::create(['nama_kegiatan' => 'Maintenance']);
        JenisKegiatan::create(['nama_kegiatan' => 'Perbaikan Alkes']);
    }
}
