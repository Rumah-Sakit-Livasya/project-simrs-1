<?php

namespace App\Imports;

use App\Models\Icd10Diagnostic;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class Icd10Import implements ToCollection, WithChunkReading, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $dataToInsert = [];
        foreach ($rows as $row) {
            // Pastikan nama kolom 'code' dan 'description' cocok dengan header di file XLSX
            if (!empty($row['code']) && !empty($row['description'])) {
                $dataToInsert[] = [
                    'code'        => $row['code'],
                    'description' => $row['description'],
                    'version'     => $row['version'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }
        // Masukkan data dalam satu query per chunk
        DB::table('icd10_diagnostics')->insert($dataToInsert);
    }

    public function chunkSize(): int
    {
        // Baca dan proses 500 baris sekaligus untuk menjaga penggunaan memori
        return 500;
    }
}
