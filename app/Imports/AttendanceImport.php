<?php

namespace App\Imports;

use App\Models\LaporanInternal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validasi jika key tidak ditemukan
            if (!isset($row['organization_id'])) {
                continue; // skip baris ini jika tidak valid
            }

            LaporanInternal::create([
                'organization_id' => $row['organization_id'],
                'user_id' => $row['user_id'],
                'tanggal' => $row['tanggal'],
                'jenis' => $row['jenis'],
                'kegiatan' => $row['kegiatan'],
                'status' => $row['status'],
                'dokumentasi' => $row['dokumentasi'] ?? null,
                'keterangan' => $row['keterangan'] ?? null,
                'jam_masuk' => $row['jam_masuk'] ?? null,
                'jam_diterima' => $row['jam_diterima'] ?? null,
                'jam_diproses' => $row['jam_diproses'] ?? null,
                'jam_selesai' => $row['jam_selesai'] ?? null,
            ]);
        }
    }
}
