<?php

namespace App\Exports;

use App\Models\Keuangan\JasaDokter;
use App\Models\SIMRS\Doctor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class JasaDokterExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithCustomStartCell
{
    protected Collection $jasaDokterItems;
    protected array $filters;
    protected string $sheetTitle;
    protected ?string $dokterName;

    public function __construct(Collection $jasaDokterItems, array $filters = [])
    {
        $this->jasaDokterItems = $jasaDokterItems;
        $this->filters = $filters;
        $this->dokterName = $this->resolveDokterName();
        $this->sheetTitle = $this->generateSheetTitle();
    }

    protected function resolveDokterName(): ?string
    {
        if (empty($this->filters['dokter_ids'])) {
            return null;
        }

        $doctors = Doctor::with('employee')
            ->whereIn('id', $this->filters['dokter_ids'])
            ->get();

        // Jika hanya satu dokter, ambil namanya dengan optional()
        if ($doctors->count() === 1) {
            return optional($doctors->first()->employee)->fullname ?? 'Tanpa Nama';
        }

        return null;
    }

    protected function generateSheetTitle(): string
    {
        $baseTitle = 'Laporan Jasa Dokter';

        if ($this->dokterName) {
            $dokterPart = ' - ' . $this->dokterName;
            // Pastikan total panjang tidak melebihi 31 karakter
            return substr($baseTitle . $dokterPart, 0, 31);
        }

        return $baseTitle;
    }

    public function collection(): Collection
    {
        return $this->jasaDokterItems;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No RM/No Reg',
            'Nama Pasien',
            'Detail Tagihan',
            'Penjamin',
            'JKP',
            'Jasa Dokter',
            'Status',
        ];
    }

    public function map($jasaDokter): array
    {
        $noRmReg = ($jasaDokter->tagihanPasien?->registration?->patient?->medical_record_number ?? '-') .
            ' / ' .
            ($jasaDokter->tagihanPasien?->registration?->registration_number ?? '-');

        $statusAp = 'Belum Dibuat AP';
        if (strtolower($jasaDokter->status) === 'final') {
            $statusAp = 'Sudah Dibuat AP';
        }

        return [
            optional($jasaDokter->tagihanPasien?->bilinganSatu)->updated_at
                ? Carbon::parse($jasaDokter->tagihanPasien->bilinganSatu->updated_at)->format('d-m-Y')
                : '-',
            $noRmReg,
            $jasaDokter->tagihanPasien->registration?->patient?->name ?? '-',
            $jasaDokter->nama_tindakan ?? '-',
            $jasaDokter->tagihanPasien->registration?->penjamin?->nama_perusahaan ?? '-',
            $jasaDokter->jkp ?? 0,
            $jasaDokter->share_dokter ?? 0,
            $statusAp,
        ];
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Create Header Information
        $this->createHeaderInfo($sheet);

        // 2. Style for Table Header (Row 6)
        $sheet->getStyle('A6:H6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
        ]);

        // 3. Set Column Widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(45);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(20);

        // 4. Style for Data Cells (Starting from row 7)
        $lastRow = $this->jasaDokterItems->count() + 6;
        if ($lastRow > 6) {
            $dataRange = 'A7:H' . $lastRow;
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBFBFBF']]],
                'alignment' => ['vertical' => 'center'],
                'font' => ['size' => 10],
            ]);

            // Specific column formatting
            $sheet->getStyle('F7:G' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('A7:A' . $lastRow)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B7:E' . $lastRow)->getAlignment()->setHorizontal('left');
            $sheet->getStyle('F7:G' . $lastRow)->getAlignment()->setHorizontal('right');
            $sheet->getStyle('H7:H' . $lastRow)->getAlignment()->setHorizontal('center');
        }

        // 5. Add Total Row
        $this->createTotalRow($sheet, $lastRow + 1);

        // 6. Freeze pane and Auto Filter
        $sheet->freezePane('A7');
        $sheet->setAutoFilter('A6:H' . $lastRow);
    }

    private function createHeaderInfo(Worksheet $sheet)
    {
        // Main Title (Row 1)
        $sheet->mergeCells('A1:H1');
        $title = $this->dokterName
            ? 'LAPORAN JASA DOKTER - ' . strtoupper($this->dokterName)
            : 'LAPORAN JASA DOKTER';
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Period Information (Row 2)
        $periodText = 'Periode: ';
        if (!empty($this->filters['tanggal_awal'])) {
            $periodText .= Carbon::parse($this->filters['tanggal_awal'])->format('d M Y');
            if (!empty($this->filters['tanggal_akhir'])) {
                $periodText .= ' s/d ' . Carbon::parse($this->filters['tanggal_akhir'])->format('d M Y');
            }
        } else {
            $periodText .= 'Semua Periode';
        }

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', $periodText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Status Information (Row 3)
        $statusText = 'Status: ';
        if (!empty($this->filters['status_ap'])) {
            $statusText .= ($this->filters['status_ap'] === 'final') ? 'Sudah Dibuat AP' : 'Belum Dibuat AP';
        } else {
            $statusText .= 'Semua Status';
        }

        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', $statusText);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Empty row for spacing (Row 4)
        $sheet->mergeCells('A4:H4');
        $sheet->getRowDimension(4)->setRowHeight(10);
    }

    private function createTotalRow(Worksheet $sheet, int $row)
    {
        // Merge cells for "TOTAL" label
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('right');

        // Calculate totals for JKP and Jasa Dokter columns
        $totalJkp = $this->jasaDokterItems->sum('jkp');
        $totalJasaDokter = $this->jasaDokterItems->sum('share_dokter');

        // Set values directly
        $sheet->setCellValue('F' . $row, $totalJkp);
        $sheet->setCellValue('G' . $row, $totalJasaDokter);

        // Style for total row
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
        ]);

        // Number formatting for totals
        $sheet->getStyle('F' . $row . ':G' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F' . $row . ':G' . $row)->getAlignment()->setHorizontal('right');
    }
}
