<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipePersalinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data tipe persalinan berdasarkan analisis dari CSV
        $tipePersalinan = [
            [
                'tipe' => 'Normal',
                'persentase' => 100,
                'operator' => true,
                'anestesi' => true,
                'prediatric' => true,
                'room' => true,
                'observasi' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'persentase' => 120,
                'operator' => true,
                'anestesi' => true,
                'prediatric' => true,
                'room' => true,
                'observasi' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Kuretase',
                'persentase' => 80,
                'operator' => true,
                'anestesi' => true,
                'prediatric' => true,
                'room' => true,
                'observasi' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Khusus',
                'persentase' => 150,
                'operator' => true,
                'anestesi' => true,
                'prediatric' => true,
                'room' => true,
                'observasi' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Paket',
                'persentase' => 100,
                'operator' => true,
                'anestesi' => true,
                'prediatric' => true,
                'room' => true,
                'observasi' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Insert data tipe persalinan
        foreach ($tipePersalinan as $tipe) {
            DB::table('tipe_persalinan')->insert($tipe);
        }

        // Output informasi
        $this->command->info('✅ Berhasil memasukkan ' . count($tipePersalinan) . ' data tipe persalinan');
        $this->command->info('📋 Detail tipe persalinan:');

        foreach ($tipePersalinan as $index => $tipe) {
            $features = [];
            if ($tipe['operator']) $features[] = 'Operator';
            if ($tipe['anestesi']) $features[] = 'Anestesi';
            if ($tipe['prediatric']) $features[] = 'Pediatric';
            if ($tipe['room']) $features[] = 'Room';
            if ($tipe['observasi']) $features[] = 'Observasi';

            $this->command->info(
                ($index + 1) . '. ' . $tipe['tipe'] .
                    ' (' . $tipe['persentase'] . '%) - ' .
                    'Fitur: ' . implode(', ', $features)
            );
        }
    }
}
