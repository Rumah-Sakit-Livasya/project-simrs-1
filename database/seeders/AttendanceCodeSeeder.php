<?php

namespace Database\Seeders;

use App\Models\AttendanceCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendancecode = [
            [
                'code' => 'I',
                'description' => 'Izin'
            ],
            [
                'code' => 'S',
                'description' => 'Sakit'
            ],
            [
                'code' => 'CT',
                'description' => 'Cuti Tahunan'
            ],
            [
                'code' => 'DDK',
                'description' => 'Dinas Dalam Kota'
            ],
            [
                'code' => 'DLK',
                'description' => 'Dinas Luar Kota'
            ],
            [
                'code' => 'IM',
                'description' => 'Izin Istri Melahirkan (special leave)'
            ],
            [
                'code' => 'CM',
                'description' => 'Cuti Menikah'
            ],
            [
                'code' => 'CMA',
                'description' => 'Cuti Menikahkan Anak'
            ],
            [
                'code' => 'CKA',
                'description' => 'Cuti Khitanan Anak'
            ],
            [
                'code' => 'CIM',
                'description' => 'Cuti Istri Melahirkan atau Keguguran'
            ],
            [
                'code' => 'CH',
                'description' => 'Cuti Haid'
            ],
            [
                'code' => 'CK',
                'description' => 'Cuti Keguguran'
            ],
            [
                'code' => 'CKM',
                'description' => 'Cuti Keluarga Meninggal'
            ],
            [
                'code' => 'CRM',
                'description' => 'Cuti Anggota Keluarga Dalam Satu Rumah Meninggal'
            ],
            [
                'code' => 'CL',
                'description' => 'Cuti Melahirkan'
            ],
            [
                'code' => 'CKTN',
                'description' => 'Cuti Menjalankan Kewajiban Terhadap Negara'
            ],
            [
                'code' => 'CTSPB',
                'description' => 'Cuti Melaksanakan Tugas Serikat Pekerja/Serikat Buruh Atas Persetujuan Pengusaha'
            ],
            [
                'code' => 'CTPDP',
                'description' => 'Cuti Melaksanakan Tugas Pendidikan Dari Perusahaan'
            ],
        ];

        foreach ($attendancecode as $data) {
            AttendanceCode::create($data);
        }
    }
}
