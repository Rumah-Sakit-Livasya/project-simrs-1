<?php

namespace App\Exports;

use App\Models\keuangan\JasaDokter;
use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class JasaDokterExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle,
    WithCustomStartCell
{
    protected $jasaDokterItems;
    protected $filters;

    public function __construct($jasaDokterItems, $filters = [])
    {
        $this->jasaDokterItems = $jasaDokterItems;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->jasaDokterItems;
    }

    public function startCell(): string
    {
        // Mulai dari baris ke-6 untuk memberikan ruang untuk header info
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'No. AP',                       // A
            'Tgl AP',                       // B
            'No. RM',                       // C
            'No. Reg',                      // D
            'Nama Pasien',                  // E
            'Tgl Bill',                     // F
            'Detail Tagihan (AP)',          // G
            'Dokter AP',                    // H
            'Penjamin (Pasien)',            // I
            'Nominal AP (Bruto)',           // J
            'Diskon AP',                    // K
            'PPN AP (%)',                   // L
            'JKP AP',                       // M
            'Jasa Dokter AP (Netto)',       // N
            'Status AP',                    // O
            'Dokter Registrasi',            // P
        ];
    }

    public function map($jasaDokter): array
    {
        return [
            $jasaDokter->ap_number ?? '-',
            $jasaDokter->ap_date ? Carbon::parse($jasaDokter->ap_date)->format('d-m-Y') : '-',
            $jasaDokter->tagihanPasien?->registration?->patient?->medical_record_number ?? '-',
            $jasaDokter->tagihanPasien?->registration?->registration_number ?? '-',
            $jasaDokter->tagihanPasien?->registration?->patient?->name ?? '-',
            $jasaDokter->bill_date ? Carbon::parse($jasaDokter->bill_date)->format('d-m-Y') : (optional($jasaDokter->tagihanPasien?->bilinganSatu?->created_at)->format('d-m-Y') ?? '-'),
            $jasaDokter->nama_tindakan ?? '-',
            optional($jasaDokter->dokter?->employee)->fullname ?? optional($jasaDokter->dokter)->nama_dokter ?? '-',
            $jasaDokter->tagihanPasien?->registration?->penjamin?->nama_perusahaan ?? '-',
            $jasaDokter->nominal ?? 0,
            $jasaDokter->diskon ?? 0,
            $jasaDokter->ppn_persen ?? 0,
            $jasaDokter->jkp ?? 0,
            $jasaDokter->jasa_dokter ?? 0,
            ucfirst($jasaDokter->status ?? 'draft'),
            optional($jasaDokter->tagihanPasien?->registration?->doctor?->employee)->fullname ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan AP Dokter';
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Membuat Header Informasi (Baris 1-4)
        $this->createHeaderInfo($sheet);

        // 2. Style untuk Header Tabel (Baris 6)
        $sheet->getStyle('A6:P6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDDDDDD'],
                ],
            ],
        ]);

        $sheet->getRowDimension(6)->setRowHeight(20);

        // 3. Mengatur Lebar Kolom Spesifik
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(25);

        // 4. Style untuk Sel Data
        $lastRow = $this->jasaDokterItems->count() + 6; // +6 karena mulai dari baris 6
        if ($lastRow > 6) {
            $sheet->getStyle('A7:P' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFBFBFBF'],
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Format angka untuk kolom numerik
            $sheet->getStyle('J7:N' . $lastRow)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // Alignment untuk kolom
            $textColumns = ['A', 'C', 'D', 'E', 'G', 'H', 'I', 'O', 'P'];
            foreach ($textColumns as $col) {
                $sheet->getStyle($col . '7:' . $col . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }

            $centerColumns = ['B', 'F'];
            foreach ($centerColumns as $col) {
                $sheet->getStyle($col . '7:' . $col . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // 5. Freeze pane
        $sheet->freezePane('A7');

        // 6. Auto Filter
        $sheet->setAutoFilter('A6:P6');

        return [];
    }

    private function createHeaderInfo(Worksheet $sheet)
    {
        // Judul Laporan
        $sheet->setCellValue('A1', 'LAPORAN ANALISIS PEMBAYARAN (AP) DOKTER');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FF2F5597'],
            ]
        ]);

        // Tanggal Export
        $sheet->setCellValue('A2', 'Tanggal Export: ' . now()->format('d-m-Y H:i:s'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true]
        ]);

        // Filter yang diterapkan
        $filterInfo = $this->getFilterInfo();
        $sheet->setCellValue('A3', 'Filter: ' . $filterInfo);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['size' => 10, 'bold' => true, 'color' => ['argb' => 'FF0066CC']]
        ]);

        // Total record
        $sheet->setCellValue('A4', 'Total Data: ' . $this->jasaDokterItems->count() . ' record');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['size' => 10, 'bold' => true]
        ]);

        // Merge cells untuk header
        $sheet->mergeCells('A1:P1');
        $sheet->mergeCells('A2:P2');
        $sheet->mergeCells('A3:P3');
        $sheet->mergeCells('A4:P4');

        // Alignment untuk header info
        $sheet->getStyle('A1:A4')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // Spacing
        $sheet->getRowDimension(5)->setRowHeight(10); // Row kosong untuk spacing
    }

    private function getFilterInfo(): string
    {
        $filters = [];

        // Periode tanggal
        if (!empty($this->filters['tanggal_awal']) && !empty($this->filters['tanggal_akhir'])) {
            $filters[] = 'Periode: ' .
                date('d-m-Y', strtotime($this->filters['tanggal_awal'])) .
                ' s/d ' .
                date('d-m-Y', strtotime($this->filters['tanggal_akhir']));
        } elseif (!empty($this->filters['tanggal_awal'])) {
            $filters[] = 'Dari Tanggal: ' . date('d-m-Y', strtotime($this->filters['tanggal_awal']));
        } elseif (!empty($this->filters['tanggal_akhir'])) {
            $filters[] = 'Sampai Tanggal: ' . date('d-m-Y', strtotime($this->filters['tanggal_akhir']));
        }

        // Dokter yang dipilih
        if (!empty($this->filters['dokter_ids']) && is_array($this->filters['dokter_ids'])) {
            $dokterNames = Doctor::with('employee')
                ->whereIn('id', $this->filters['dokter_ids'])
                ->get()
                ->pluck('employee.fullname')
                ->filter()
                ->toArray();

            if (!empty($dokterNames)) {
                if (count($dokterNames) == 1) {
                    $filters[] = 'Dokter: ' . $dokterNames[0];
                } else {
                    $filters[] = 'Dokter: ' . implode(', ', array_slice($dokterNames, 0, 3)) .
                        (count($dokterNames) > 3 ? ' (dan ' . (count($dokterNames) - 3) . ' lainnya)' : '');
                }
            }
        }

        // Status AP
        if (!empty($this->filters['status_ap'])) {
            $filters[] = 'Status AP: ' . ucfirst($this->filters['status_ap']);
        }

        // Tipe Registrasi
        if (!empty($this->filters['tipe_registrasi'])) {
            $filters[] = 'Tipe Registrasi: ' . $this->filters['tipe_registrasi'];
        }

        // Status Pembayaran
        if (!empty($this->filters['tagihan_pasien'])) {
            $filters[] = 'Status Pembayaran: ' . ucfirst(str_replace('-', ' ', $this->filters['tagihan_pasien']));
        }

        return !empty($filters) ? implode(' | ', $filters) : 'Semua Data';
    }
}
