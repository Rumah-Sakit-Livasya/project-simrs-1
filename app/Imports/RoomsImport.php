<?php

namespace App\Imports;

use App\Models\SIMRS\Room;
use App\Models\SIMRS\KelasRawat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoomsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari KelasRawat berdasarkan nama
        $kelasRawat = KelasRawat::where('nama_kelas', $row['nama_kelas_rawat'])->first();

        // Jika kelas rawat tidak ditemukan, lewati baris ini
        if (!$kelasRawat) {
            return null;
        }

        return Room::updateOrCreate(
            ['nama_ruangan' => $row['nama_ruangan']], // Cari berdasarkan nama ruangan
            [
                'kelas_rawat_id' => $kelasRawat->id, // Gunakan ID dari kelas yang ditemukan
                'keterangan'     => $row['keterangan'],
            ]
        );
    }
}
