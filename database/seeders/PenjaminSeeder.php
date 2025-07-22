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
                'group_penjamin_id' => 1,
                'mulai_kerjasama' => '2023-01-01',
                'akhir_kerjasama' => '2024-01-01',
                'tipe_perusahaan' => 'Asuransi Penjamin',
                'nama_perusahaan' => 'Umum',
                'kode_perusahaan' => 'Umum',
                'nama_kontak' => 'Mas',
                'jenis_kerjasama' => 'RWI, RWJ',
                'jenis_kontrak' => 'Kontrak',
                'keterangan' => '-',
            ],
            [
                'group_penjamin_id' => 2,
                'mulai_kerjasama' => '2023-01-01',
                'akhir_kerjasama' => '2024-01-01',
                'tipe_perusahaan' => 'Asuransi Penjamin',
                'nama_perusahaan' => 'BPJS Kesehatan',
                'kode_perusahaan' => 'BPJS',
                'nama_kontak' => 'Mas',
                'jenis_kerjasama' => 'RWI, RWJ',
                'jenis_kontrak' => 'Kontrak',
                'keterangan' => '-',
            ],
            [
                'group_penjamin_id' => 3,
                'mulai_kerjasama' => '2023-01-01',
                'akhir_kerjasama' => '2024-01-01',
                'tipe_perusahaan' => 'Asuransi Penjamin',
                'nama_perusahaan' => 'Asuransi',
                'kode_perusahaan' => 'Asuransi',
                'nama_kontak' => 'Mas',
                'jenis_kerjasama' => 'RWI, RWJ',
                'jenis_kontrak' => 'Kontrak',
                'keterangan' => '-',
            ],
        ];

        foreach ($penjamin as $p) {
            Penjamin::create($p);
        }
    }
}
