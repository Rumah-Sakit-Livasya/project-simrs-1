<?php

namespace Database\Seeders;

use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\TindakanMedis;
use App\Models\TarifTindakanMedis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifTindakanSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/TarifTindakanSeeder.php
    public function run()
    {
        $tindakan = TindakanMedis::find(1); // Konsultasi Dokter Spesialis
        $penjamin = Penjamin::find(1); // BPJS

        if ($tindakan && $penjamin) {
            TarifTindakanMedis::firstOrCreate([
                'tindakan_medis_id' => $tindakan->id,
                'group_penjamin_id' => $penjamin->group_penjamin_id, // Perhatikan ini
                'kelas_rawat_id' => 1 // Rawat Jalan
            ], [
                'share_dr' => 10000,
                'share_rs' => 10000,
                'total' => 20000
            ]);
        }
    }
}
