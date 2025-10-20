<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Tambahkan orderBy fullname
        return Employee::query()
            ->with(['organization', 'jobPosition', 'jobLevel', 'company'])
            ->where('is_active', 1)
            ->orderBy('fullname', 'asc');
    }

    /**
     * @param \App\Models\Employee $employee
     * @return array
     */
    public function map($employee): array
    {
        return [
            $employee->employee_code,
            $employee->fullname,
            $employee->email,
            $employee->mobile_phone,
            $employee->jobPosition->name ?? '-',
            $employee->jobLevel->name ?? '-',
            $employee->organization->name ?? '-',
            $employee->company->name ?? '-',
            $employee->employment_status,
            $employee->join_date,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Email',
            'No. HP',
            'Jabatan',
            'Level',
            'Unit/Organisasi',
            'Perusahaan',
            'Status Pegawai',
            'Tanggal Bergabung',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        // Mengatur border pada seluruh data termasuk heading
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();

        $dataRange = "A1:{$highestColumn}{$highestRow}";

        // Mengatur border tipis di seluruh area data
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Optional: bold heading
        $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);

        return [];
    }
}
