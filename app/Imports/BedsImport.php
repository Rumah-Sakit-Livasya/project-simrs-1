<?php

namespace App\Imports;

use App\Models\SIMRS\Bed;
use App\Models\SIMRS\Room;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BedsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari Room berdasarkan nama
        $room = Room::where('nama_ruangan', $row['nama_ruangan'])->first();

        // Jika ruangan tidak ditemukan, lewati baris ini
        if (!$room) {
            return null;
        }

        return Bed::updateOrCreate(
            [
                'room_id'   => $room->id, // Cari berdasarkan kombinasi
                'nomor_bed' => $row['nomor_bed'],
            ],
            [
                'status'      => $row['status'] ?? 'Tersedia', // Default value jika kosong
                'is_tambahan' => $row['apakah_bed_tambahan_1ya_0tidak'],
            ]
        );
    }
}
