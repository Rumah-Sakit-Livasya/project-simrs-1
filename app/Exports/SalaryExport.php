<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SalaryExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $organizationId;
    protected $employeeId;

    public function __construct($organizationId, $employeeId)
    {
        $this->organizationId = $organizationId;
        $this->employeeId = $employeeId;
    }

    public function query()
    {
        $query = Employee::select(
            'employees.id',
            'employees.fullname',
            'organizations.name as organization_name',
            'salaries.basic_salary',
            'salaries.tunjangan_jabatan',
            'salaries.tunjangan_profesi',
            'salaries.tunjangan_makan_dan_transport',
            'salaries.tunjangan_masa_kerja',
            'salaries.guarantee_fee',
            'salaries.uang_duduk',
            'salaries.tax_allowance'
        )
            ->leftJoin('organizations', 'employees.organization_id', '=', 'organizations.id')
            ->leftJoin('salaries', 'employees.id', '=', 'salaries.employee_id')
            ->where('employees.is_active', 1); // Kondisi umum untuk semua query

        // Filter berdasarkan organizationId jika ada
        if (!empty($this->organizationId)) {
            $query->where('employees.organization_id', $this->organizationId);
        }

        // Filter berdasarkan employeeId jika ada
        if (!empty($this->employeeId)) {
            $query->where('employees.id', $this->employeeId);
        }


        return $query;
    }

    public function headings(): array
    {
        return [
            'No', // Kolom nomor
            'Fullname',
            'Organization Name',
            'Basic Salary',
            'Tunjangan Jabatan',
            'Tunjangan Profesi',
            'Tunjangan Makan & Transport',
            'Tunjangan Masa Kerja',
            'Guarantee Fee',
            'Uang Duduk',
            'Tax Allowance'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Tambahkan border untuk seluruh sel data
                $event->sheet->getDelegate()->getStyle('A1:' . $event->sheet->getHighestDataColumn() . $event->sheet->getHighestDataRow())
                    ->getBorders()->getAllBorders()->setBorderStyle('thin');
            },
        ];
    }

    public function map($employee): array
    {
        static $row = 0;

        return [
            ++$row, // Nomor berurutan
            $employee->fullname,
            $employee->organization_name,
            $employee->basic_salary ?? 0,
            $employee->tunjangan_jabatan ?? 0,
            $employee->tunjangan_profesi ?? 0,
            $employee->tunjangan_makan_dan_transport ?? 0,
            $employee->tunjangan_masa_kerja ?? 0,
            $employee->guarantee_fee ?? 0,
            $employee->uang_duduk ?? 0,
            $employee->tax_allowance ?? 0
        ];
    }
}
