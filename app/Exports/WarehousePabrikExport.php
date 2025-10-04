<?php

namespace App\Exports;

use App\Models\WarehousePabrik;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarehousePabrikExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return WarehousePabrik::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama',
            'alamat',
            'telp',
            'contact_person',
            'contact_person_phone',
            'aktif',
        ];
    }

    /**
     * @param WarehousePabrik $pabrik
     * @return array
     */
    public function map($pabrik): array
    {
        return [
            $pabrik->nama,
            $pabrik->alamat,
            $pabrik->telp,
            $pabrik->contact_person,
            $pabrik->contact_person_phone,
            $pabrik->aktif ? '1' : '0', // Export boolean as 1 or 0
        ];
    }
}
