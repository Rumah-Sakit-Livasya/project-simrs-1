<?php

namespace Database\Seeders;

use App\Models\SIMRS\GroupPenjamin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupPenjaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjamin = [
            [
                'name' => 'Standar',
                'code' => 'Standar',
            ],
            [
                'name' => 'BPJS',
                'code' => 'BPJS',
            ],
            [
                'name' => 'Asuransi',
                'code' => 'Asuransi',
            ],
        ];

        foreach ($penjamin as $p) {
            GroupPenjamin::create($p);
        }
    }
}
