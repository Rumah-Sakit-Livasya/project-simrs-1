<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollDeductionsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function query()
    {
        $query = Employee::query()->select(
            'employees.email',
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

        return $query;
    }

    public function headings(): array
    {
        return [
            'Email', // Kolom nomor
            'Fullname',
            'Organization Name',
            'Periode',
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
            $employee->email,
            $employee->fullname,
            $employee->organization_name ?? '-',
            $this->periode,
            (string) ($employee->potongan_keterlambatan ?? '0'),
            (string) ($employee->potongan_izin ?? '0'),
            (string) ($employee->potongan_sakit ?? '0'),
            (string) ($employee->simpanan_pokok ?? '0'),
            (string) ($employee->potongan_koperasi ?? '0'),
            (string) ($employee->potongan_absensi ?? '0'),
            (string) ($employee->potongan_bpjs_kesehatan ?? '0'),
            (string) ($employee->potongan_bpjs_ketenagakerjaan ?? '0'),
            (string) ($employee->potongan_pajak ?? '0')
        ];
    }
}
