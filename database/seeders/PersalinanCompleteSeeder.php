<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PersalinanCompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data lengkap persalinan berdasarkan CSV
        $persalinanData = [
            [
                'tipe' => 'Patologis',
                'kode' => 'VK001',
                'nama_persalinan' => 'PARTUS GEMELI',
                'nama_billing' => 'Persalinan Kembar',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK002',
                'nama_persalinan' => 'PARTUS PATOLOGIS DENGAN RIWAYAT SC',
                'nama_billing' => 'Persalinan Patologis dengan Riwayat SC',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK003',
                'nama_persalinan' => 'PARTUS PATOLOGIS EKLAMPSI',
                'nama_billing' => 'Persalinan Patologis dengan Eklampsi',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK004',
                'nama_persalinan' => 'PARTUS PATOLOGIS KPD',
                'nama_billing' => 'Persalinan Patologis dengan KPD',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK005',
                'nama_persalinan' => 'PARTUS PATOLOGIS OLIGOHIDRAMNION',
                'nama_billing' => 'Persalinan Patologis dengan Oligohidramnion',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK006',
                'nama_persalinan' => 'PARTUS PATOLOGIS OXITOCIN DRIP',
                'nama_billing' => 'Persalinan Patologis dengan Oxitocin Drip',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK007',
                'nama_persalinan' => 'PARTUS PATOLOGIS PEB',
                'nama_billing' => 'Persalinan Patologis dengan PEB',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK008',
                'nama_persalinan' => 'PARTUS PATOLOGIS SUNGSANG',
                'nama_billing' => 'Persalinan Patologis Sungsang',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK009',
                'nama_persalinan' => 'PARTUS PATOLOGIS VCE',
                'nama_billing' => 'Persalinan Patologis dengan VCE',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Normal',
                'kode' => 'VK010',
                'nama_persalinan' => 'PARTUS SPONTAN',
                'nama_billing' => 'Persalinan Spontan',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Kuretase',
                'kode' => 'VK011',
                'nama_persalinan' => 'KURETASE ABORTUS',
                'nama_billing' => 'Kuretase Abortus',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Kuretase',
                'kode' => 'VK012',
                'nama_persalinan' => 'KURETASE PASCA PERSALINAN (SISA PLACENTA)',
                'nama_billing' => 'Kuretase Pasca Persalinan (Sisa Placenta)',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Khusus',
                'kode' => 'VK013',
                'nama_persalinan' => 'BIOPSI SERVIKS',
                'nama_billing' => 'Biopsi Serviks',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Paket',
                'kode' => 'VK014',
                'nama_persalinan' => 'PAKET PARTUS 1',
                'nama_billing' => 'Paket Persalinan 1',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Patologis',
                'kode' => 'VK015',
                'nama_persalinan' => 'PARTUS PATOLOGIS',
                'nama_billing' => 'Persalinan Patologis Umum',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'tipe' => 'Khusus',
                'kode' => 'VK016',
                'nama_persalinan' => 'EKTIRPASI',
                'nama_billing' => 'Ektirpasi',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Insert data persalinan
        foreach ($persalinanData as $persalinan) {
            DB::table('persalinan')->insert($persalinan);
        }

        // Output informasi
        $this->command->info('✅ Berhasil memasukkan ' . count($persalinanData) . ' data persalinan');
        $this->command->info('📋 Data yang dimasukkan:');

        foreach ($persalinanData as $index => $persalinan) {
            $this->command->info(($index + 1) . '. ' . $persalinan['kode'] . ' - ' . $persalinan['nama_persalinan']);
        }
    }
}
