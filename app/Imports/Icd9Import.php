<?php

namespace App\Imports;

use App\Models\Icd9Procedure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class Icd9Import implements ToCollection, WithChunkReading, WithHeadingRow
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
        DB::table('icd9_procedures')->insert($dataToInsert);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
