<?php

namespace App\Exports;

use App\Models\SIMRS\Bed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BedsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Bed::with('room')->get();
    }

    public function headings(): array
    {
        return [
            'ID Bed',
            'Nama Ruangan', // Dari relasi
            'Nama Bed',
            'Nomor Bed',
            'Apakah Bed Tambahan? (1=Ya, 0=Tidak)',
        ];
    }

    /**
     * @var Bed $bed
     */
    public function map($bed): array
    {
        return [
            $bed->id,
            $bed->room->ruangan ?? 'N/A', // Tampilkan nama ruangan
            $bed->nama_tt,
            $bed->no_tt,
            $bed->is_tambahan ?? 0,
        ];
    }
}
