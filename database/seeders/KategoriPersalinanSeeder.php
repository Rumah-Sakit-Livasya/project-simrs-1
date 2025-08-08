<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriPersalinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data kategori persalinan
        $kategoriPersalinan = [
            [
                'nama' => 'Persalinan Normal',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nama' => 'Persalinan Patologis',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nama' => 'Tindakan Kuretase',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nama' => 'Tindakan Khusus',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nama' => 'Paket Persalinan',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Insert data kategori persalinan
        foreach ($kategoriPersalinan as $kategori) {
            DB::table('kategori_persalinan')->insert($kategori);
        }

        // Output informasi
        $this->command->info('✅ Berhasil memasukkan ' . count($kategoriPersalinan) . ' data kategori persalinan');

        foreach ($kategoriPersalinan as $index => $kategori) {
            $this->command->info(($index + 1) . '. ' . $kategori['nama'] . ' (' . ($kategori['is_aktif'] ? 'Aktif' : 'Non-Aktif') . ')');
        }
    }
}
