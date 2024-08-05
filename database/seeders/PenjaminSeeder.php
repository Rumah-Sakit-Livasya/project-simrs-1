<?php

namespace Database\Seeders;

use App\Models\SIMRS\Penjamin;
use Illuminate\Database\Seeder;

class PenjaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penjamin = [
            [
                'name' => 'Umum',
            ],
            [
                'name' => 'BPJS',
            ],
            [
                'name' => 'Asuransi',
            ],
        ];

        foreach ($penjamin as $p) {
            Penjamin::create($p);
        }
    }
}
