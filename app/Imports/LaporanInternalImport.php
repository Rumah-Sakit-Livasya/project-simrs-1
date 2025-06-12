<?php

namespace App\Imports;

use App\Models\LaporanInternal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LaporanInternalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new LaporanInternal([
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
