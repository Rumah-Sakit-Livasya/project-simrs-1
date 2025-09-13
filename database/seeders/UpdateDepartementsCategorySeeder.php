<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SIMRS\Departement;

class UpdateDepartementsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = [
            // Kategori paling spesifik: POLIKLINIK
            'OBGYN' => 'poliklinik',
            'ANAK' => 'poliklinik',
            'BEDAH UMUM' => 'poliklinik',
            'PENYAKIT DALAM' => 'poliklinik',
            'PARU' => 'poliklinik',
            'JANTUNG' => 'poliklinik',
            'JIWA' => 'poliklinik',
            'REHAB MEDIK' => 'poliklinik',
            'THT' => 'poliklinik',
            'UMUM' => 'poliklinik',
            'KLINIK GIGI DAN MULUT' => 'poliklinik',
            'MEDICAL CHECK UP' => 'poliklinik',
            'FISIOTERAPI RM' => 'poliklinik',
            'KLINIK RADIOLOGI' => 'poliklinik',
            'HOMECARE' => 'poliklinik',

            // Kategori paling spesifik: RAWAT INAP
            'RAWAT INAP' => 'rawat_inap',
            'ICU/ICCU' => 'rawat_inap',
            'BABY CARE & MOTHER' => 'rawat_inap',
            'ONE DAY CARE' => 'rawat_inap',
            'TINDAKAN KEPERAWATAN PERINATOLOGI' => 'rawat_inap',

            // Kategori paling spesifik: PENUNJANG MEDIS
            'HEMODIALISA' => 'penunjang_medis',
            'ANASTESI' => 'penunjang_medis',
            'INSTALASI FARMASI' => 'penunjang_medis',
            'UNIT GIZI' => 'penunjang_medis',
            'LAB' => 'penunjang_medis',
            'RADIOLOGI' => 'penunjang_medis',
            'PATALOGI KLINIK' => 'penunjang_medis',
            'UGD' => 'penunjang_medis',
            'AMBULANCE' => 'penunjang_medis',

            // Kategori paling spesifik: LAINNYA (Administratif, dll)
            'FACILITY' => 'lainnya',
            'FEE RUJUKAN OK' => 'lainnya',
            'FEE RUJUKAN VK' => 'lainnya',
            'KORESPONDEDSI' => 'lainnya',
            'PAKET PARTUS' => 'lainnya',
            'TINDAKAN KEPERAWATAN VK' => 'lainnya',
            'TINDAKAN PERAWATAN' => 'lainnya',
            'VISITE' => 'lainnya',
        ];

        foreach ($categoryMap as $kode => $category) {
            Departement::where('kode', trim($kode))->update(['category' => $category]);
        }
    }
}
