<?php

namespace App\Exports;

use App\Models\Sip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SipsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Sip::with('employee:id,fullname')->get();
    }
    public function headings(): array
    {
        return ['ID Pegawai', 'Nama Pegawai', 'Nomor SIP', 'Tanggal Kadaluarsa SIP'];
    }
    public function map($sip): array
    {
        return [$sip->employee_id, $sip->employee ? $sip->employee->fullname : 'N/A', $sip->sip_number, $sip->sip_expiry_date];
    }
}
