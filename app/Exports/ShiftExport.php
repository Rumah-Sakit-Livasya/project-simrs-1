<?php

namespace App\Exports;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ShiftExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $employees;
    protected $month;
    protected $year;

    public function __construct($employees, $month, $year)
    {
        $this->employees = $employees;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        $headings = ['Employee ID', 'Email', 'Employee Name'];

        $startDate = Carbon::create($this->year, $this->month - 1, 26);
        $endDate = $startDate->copy()->addMonth()->subDay();

        while ($startDate <= $endDate) {
            $headings[] = $startDate->format('d-m-Y');
            $startDate->addDay();
        }

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $startDate = Carbon::create($this->year, $this->month - 1, 26);
                $endDate = $startDate->copy()->addMonth()->subDay();

                $columnIndex = 4; // Dimulai setelah kolom nama
                $rowIndex = 2; // Data karyawan dimulai dari baris kedua

                foreach ($this->employees as $employee) {
                    $currentColumn = $columnIndex;

                    foreach (range(0, $endDate->diffInDays($startDate)) as $offset) {
                        $currentDate = $startDate->copy()->addDays($offset);
                        $attendance = $employee->attendance->firstWhere('date', $currentDate->format('Y-m-d'));
                        $shift = $attendance?->shift?->name ?? ($attendance?->attendance_code?->code ?? $attendance?->day_off?->attendance_code?->code);
                        $sheet->setCellValueByColumnAndRow($currentColumn, $rowIndex, $shift);
                        $currentColumn++;
                    }
                    $rowIndex++;
                }

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $range = "A1:{$highestColumn}{$highestRow}";

                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
