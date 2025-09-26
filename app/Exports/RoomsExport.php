<?php

namespace App\Exports;

use App\Models\SIMRS\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Room::with('kelas_rawat')->get();
    }

    public function headings(): array
    {
        return [
            'ID Ruangan',
            'Nama Kelas Rawat', // Dari relasi
            'Nama Ruangan',
            'Keterangan',
        ];
    }

    /**
     * @var Room $room
     */
    public function map($room): array
    {
        return [
            $room->id,
            $room->kelas_rawat->kelas ?? 'N/A', // Tampilkan nama kelas
            $room->ruangan,
            $room->keterangan,
        ];
    }
}
