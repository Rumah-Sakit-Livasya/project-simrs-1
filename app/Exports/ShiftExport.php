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
    protected $shifts; // Tambahkan properti shifts

    public function __construct($employees, $shifts, $month, $year)
    {
        $this->employees = $employees;
        $this->shifts = $shifts; // Inisialisasi shifts
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        // Atur judul kolom untuk data karyawan
        $headings = [
            'Employee ID',
            'Email',
            'Employee Name',
        ];

        // Tentukan tanggal awal dan akhir berdasarkan bulan dan tahun yang dipilih
        $startDate = Carbon::createFromDate($this->year, $this->month - 1, 26);
        $endDate = Carbon::createFromDate($this->year, $this->month - 1, 25)->addMonth();

        // Buat judul kolom berdasarkan rentang tanggal
        while ($startDate <= $endDate) {
            $headings[] = $startDate->format('d-m-Y');
            $startDate->addDay();
        }

        return $headings;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $startDate = Carbon::createFromDate($this->year, $this->month - 1, 25);
                $endDate = Carbon::createFromDate($this->year, $this->month - 1, 25)->addMonth();

                // Menambahkan data nama shift di samping nama employee
                $startDateColumnIndex = 4; // Kolom pertama setelah Employee Name
                $startDateRowIndex = 2; // Baris kedua (setelah header)

                foreach ($this->employees as $employee) {
                    $currentColumnIndex = $startDateColumnIndex;
                    foreach ($this->headings() as $index => $heading) {
                        if ($index < 3) { // Skip Employee ID, Email, and Employee Name columns
                            continue;
                        }

                        $dateColumnIndex = $index + 1; // Kolom tanggal dimulai dari indeks ke-4
                        $attendanceDate = Carbon::createFromFormat('d-m-Y', $heading)->startOfDay();

                        if ($attendanceDate->gte($startDate) && $attendanceDate->lte($endDate)) {
                            // Tanggal pada attendance berada di antara startDate dan endDate
                            $shift = null;
                            $attendance = $employee->attendance->where('date', $attendanceDate->format('Y-m-d'))->first();
                            if ($attendance) {
                                if($attendance->shift) {
                                $shift = $attendance->shift->name;
                                    if ($attendance->attendance_code || $attendance->day_off) {
                                        $shift = $attendance->attendance_code->code ?? $attendance->day_off->attendance_code->code;
                                    }
                                }
                            }
                            $event->sheet->setCellValueByColumnAndRow($currentColumnIndex, $startDateRowIndex, $shift);
                        }

                        $currentColumnIndex++;
                    }
                    $startDateRowIndex++;
                }

                // Menambahkan border ke setiap sel dalam lembar Excel
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $range = 'A1:' . $highestColumn . $highestRow; // Mulai dari kolom Employee Name
                $event->sheet->getStyle($range)->applyFromArray([
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
