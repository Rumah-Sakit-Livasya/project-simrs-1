<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StrTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        $employees = Employee::where('is_active', 1)->with('strs')->orderBy('fullname')->get();
        $rows = [];
        foreach ($employees as $employee) {
            $str = $employee->strs; // assume hasOne relation named 'str'
            if ($str) {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    $str->str_number,
                    $str->is_lifetime ? 'YA' : 'TIDAK',
                    $str->str_expiry_date,
                ];
            } else {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    '',
                    'TIDAK',
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
            'str_number (Wajib)',
            'is_lifetime (Isi "YA" jika seumur hidup, selain itu "TIDAK")',
            'str_expiry_date (Wajib jika tidak seumur hidup, format: YYYY-MM-DD)',
        ];
    }
}
