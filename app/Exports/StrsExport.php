<?php

namespace App\Exports;

use App\Models\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StrsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Str::with('employee:id,fullname')->get();
    }
    public function headings(): array
    {
        return ['ID Pegawai', 'Nama Pegawai', 'Nomor STR', 'Tanggal Kadaluarsa STR'];
    }
    public function map($str): array
    {
        return [$str->employee_id, $str->employee ? $str->employee->fullname : 'N/A', $str->str_number, $str->str_expiry_date];
    }
}
