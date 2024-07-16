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

class DeductionExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
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
        $query = Employee::query()->select(
            'employees.id',
            'employees.fullname',
            'organizations.name as organization_name',
            'deductions.potongan_keterlambatan',
            'deductions.potongan_izin',
            'deductions.potongan_sakit',
            'deductions.simpanan_pokok',
            'deductions.potongan_koperasi',
            'deductions.potongan_absensi',
            'deductions.potongan_bpjs_kesehatan',
            'deductions.potongan_bpjs_ketenagakerjaan',
            'deductions.potongan_pajak'
        )->leftJoin('organizations', 'employees.organization_id', '=', 'organizations.id')
            ->leftJoin('deductions', 'employees.id', '=', 'deductions.employee_id');

        if ($this->organizationId) {
            $query->where('employees.organization_id', $this->organizationId);
        }

        if ($this->employeeId) {
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
            'Potongan Keterlambatan',
            'Potongan Izin',
            'Potongan Sakit',
            'Simpanan Pokok',
            'Potongan Koperasi',
            'Potongan Absensi',
            'Potongan BPJS Kesehatan',
            'Potongan BPJS Ketenagakerjaan',
            'Potongan Pajak'
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
            $employee->potongan_keterlambatan ?? 0,
            $employee->potongan_izin ?? 0,
            $employee->potongan_sakit ?? 0,
            $employee->simpanan_pokok ?? 0,
            $employee->potongan_koperasi ?? 0,
            $employee->potongan_absensi ?? 0,
            $employee->potongan_bpjs_kesehatan ?? 0,
            $employee->potongan_bpjs_ketenagakerjaan ?? 0,
            $employee->potongan_pajak ?? 0
        ];
    }
}
