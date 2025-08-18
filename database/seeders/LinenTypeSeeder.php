<?php

namespace Database\Seeders;

use App\Models\LinenType;
use Illuminate\Database\Seeder;

class LinenTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Sprei', 'Sarung Bantal', 'Apron', 'Perlak', 'Set OK', 'Bed Cover', 'Handuk Kecil'];
        foreach ($types as $type) {
            LinenType::create(['name' => $type]);
        }
    }
}
