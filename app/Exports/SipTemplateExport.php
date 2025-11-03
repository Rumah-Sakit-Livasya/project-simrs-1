<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SipTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        $employees = Employee::where('is_active', 1)->with('sips')->orderBy('fullname')->get();
        $rows = [];
        foreach ($employees as $employee) {
            $sip = $employee->sips;
            if ($sip) {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    $sip->sip_number,
                    $sip->sip_expiry_date,
                ];
            } else {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    '',
                    '',
                ];
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'employee_id (Jangan diubah)',
            'employee_fullname (Jangan diubah)',
            'sip_number (Wajib)',
            'sip_expiry_date (Wajib, format: YYYY-MM-DD)',
        ];
    }
}
