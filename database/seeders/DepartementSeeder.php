<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SIMRS\Departement;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        // Data lengkap dengan kategori yang sudah ditentukan
        $departmentsData = [
            // Kategori: poliklinik
            ["id" => 3, "name" => "KLINIK OBGYN", "kode" => "OBGYN", "keterangan" => "KLINIK OBGYN", "category" => "poliklinik"],
            ["id" => 4, "name" => "KLINIK SPESIALIS ANAK", "kode" => "ANAK", "keterangan" => "KLINIK ANAK", "category" => "poliklinik"],
            ["id" => 5, "name" => "KLINIK BEDAH UMUM", "kode" => "BEDAH UMUM", "keterangan" => "KLINIK BEDAH UMUM", "category" => "poliklinik"],
            ["id" => 6, "name" => "KLINIK PENYAKIT DALAM", "kode" => "PENYAKIT DALAM", "keterangan" => "KLINIK PENYAKIT DALAM", "category" => "poliklinik"],
            ["id" => 7, "name" => "KLINIK PARU", "kode" => "PARU", "keterangan" => "KLINIK PARU", "category" => "poliklinik"],
            ["id" => 18, "name" => "KLINIK GIGI DAN MULUT", "kode" => "KLINIK GIGI DAN MULUT", "keterangan" => "KLINIK GIGI DAN MULUT", "category" => "poliklinik"],
            ["id" => 19, "name" => "KLINIK JANTUNG", "kode" => "JANTUNG", "keterangan" => "KLINIK JANTUNG", "category" => "poliklinik"],
            ["id" => 20, "name" => "KLINIK JIWA", "kode" => "JIWA", "keterangan" => "KLINIK JIWA", "category" => "poliklinik"],
            ["id" => 21, "name" => "KLINIK REHAB MEDIK", "kode" => "REHAB MEDIK", "keterangan" => "KLINIK REHAB MEDIK", "category" => "poliklinik"],
            ["id" => 22, "name" => "KLINIK THT", "kode" => "THT", "keterangan" => "KLINIK THT", "category" => "poliklinik"],
            ["id" => 23, "name" => "KLINIK UMUM", "kode" => "UMUM", "keterangan" => "KLINIK UMUM", "category" => "poliklinik"],
            ["id" => 25, "name" => "MEDICAL CHECK UP", "kode" => "MEDICAL CHECK UP", "keterangan" => "MEDICAL CHECK UP", "category" => "poliklinik"],
            ["id" => 29, "name" => "REHAB MEDIK", "kode" => "REHAB MEDIK", "keterangan" => "REHAB MEDIK", "category" => "poliklinik"],
            ["id" => 35, "name" => "FISIOTERAPI RM", "kode" => "FISIOTERAPI RM", "keterangan" => "FISIOTERAPI RM", "category" => "poliklinik"],
            ["id" => 39, "name" => "KLINIK RADIOLOGI", "kode" => "KLINIK RADIOLOGI", "keterangan" => "KLINIK RADIOLOGI", "category" => "poliklinik"],
            ["id" => 15, "name" => "HOMECARE", "kode" => "HOMECARE", "keterangan" => "HOMECARE", "category" => "poliklinik"],

            // Kategori: rawat_inap
            ["id" => 10, "name" => "BABY CARE & MOTHER", "kode" => "BABY CARE & MOTHER", "keterangan" => "BABY CARE & MOTHER", "category" => "rawat_inap"],
            ["id" => 16, "name" => "ICU/ICCU", "kode" => "ICU/ICCU", "keterangan" => "ICU/ICCU", "category" => "rawat_inap"],
            ["id" => 26, "name" => "ONE DAY CARE", "kode" => "ONE DAY CARE", "keterangan" => "ONE DAY CARE", "category" => "rawat_inap"],
            ["id" => 28, "name" => "RAWAT INAP", "kode" => "RAWAT INAP", "keterangan" => "RAWAT INAP", "category" => "rawat_inap"],
            ["id" => 40, "name" => "TINDAKAN KEPERAWATAN PERINATOLOGI", "kode" => "TINDAKAN KEPERAWATAN PERINATOLOGI", "keterangan" => "TINDAKAN KEPERAWATAN PERINATOLOGI", "category" => "rawat_inap"],

            // Kategori: penunjang_medis
            ["id" => 9, "name" => "ANASTESI", "kode" => "ANASTESI", "keterangan" => "ANASTESI", "category" => "penunjang_medis"],
            ["id" => 14, "name" => "HEMODIALISA", "kode" => "HEMODIALISA", "keterangan" => "HEMODIALISA", "category" => "penunjang_medis"],
            ["id" => 17, "name" => "INSTALASI FARMASI", "kode" => "INSTALASI FARMASI", "keterangan" => "INSTALASI FARMASI", "category" => "penunjang_medis"],
            ["id" => 32, "name" => "UNIT GIZI", "kode" => "UNIT GIZI", "keterangan" => "UNIT GIZI", "category" => "penunjang_medis"],
            ["id" => 33, "name" => "LAB", "kode" => "LAB", "keterangan" => "UNIT LABORATORIUM", "category" => "penunjang_medis"],
            ["id" => 34, "name" => "UNIT RADIOLOGI", "kode" => "RADIOLOGI", "keterangan" => "UNIT RADIOLOGI", "category" => "penunjang_medis"],
            ["id" => 37, "name" => "UGD", "kode" => "UGD", "keterangan" => "UGD", "category" => "penunjang_medis"],
            ["id" => 38, "name" => "PATALOGI KLINIK", "kode" => "PATALOGI KLINIK", "keterangan" => "PATALOGI KLINIK", "category" => "penunjang_medis"],
            ["id" => 8, "name" => "AMBULANCE", "kode" => "AMBULANCE", "keterangan" => "AMBULANCE", "category" => "penunjang_medis"],

            // Kategori: lainnya
            ["id" => 11, "name" => "FACILITY", "kode" => "FACILITY", "keterangan" => "FACILITY", "category" => "lainnya"],
            ["id" => 12, "name" => "FEE RUJUKAN OK", "kode" => "FEE RUJUKAN OK", "keterangan" => "FEE RUJUKAN OK", "category" => "lainnya"],
            ["id" => 13, "name" => "FEE RUJUKAN VK", "kode" => "FEE RUJUKAN VK", "keterangan" => "FEE RUJUKAN VK", "category" => "lainnya"],
            ["id" => 24, "name" => "KORESPONDEDSI", "kode" => "KORESPONDEDSI", "keterangan" => "KORESPONDEDSI", "category" => "lainnya"],
            ["id" => 27, "name" => "PAKET PARTUS", "kode" => "PAKET PARTUS", "keterangan" => "PAKET PARTUS", "category" => "lainnya"],
            ["id" => 30, "name" => "TINDAKAN KEPERAWATAN VK", "kode" => "TINDAKAN KEPERAWATAN VK", "keterangan" => "TINDAKAN KEPERAWATAN VK", "category" => "lainnya"],
            ["id" => 31, "name" => "TINDAKAN PERAWATAN", "kode" => "TINDAKAN PERAWATAN", "keterangan" => "TINDAKAN PERAWATAN", "category" => "lainnya"],
            ["id" => 36, "name" => "VISITE", "kode" => "VISITE", "keterangan" => "VISITE", "category" => "lainnya"],
        ];

        foreach ($departmentsData as $data) {
            Departement::updateOrCreate(
                ['id' => $data['id']], // Cari berdasarkan ID
                [
                    'name' => $data['name'],
                    'kode' => trim($data['kode']),
                    'keterangan' => $data['keterangan'],
                    'category' => $data['category'], // Langsung isi kategorinya
                ]
            );
        }
    }
}
