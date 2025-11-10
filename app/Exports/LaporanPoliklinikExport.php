<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPoliklinikExport implements FromArray, WithEvents, WithColumnWidths, ShouldAutoSize
{
    protected $results;
    protected $filter;
    protected $poliklinik;
    protected $dokter;
    protected $penjamin;

    public function __construct($results, $filter, $poliklinik, $dokter, $penjamin)
    {
        $this->results = $results;
        $this->filter = $filter;
        $this->poliklinik = $poliklinik;
        $this->dokter = $dokter;
        $this->penjamin = $penjamin;
    }

    /**
     * Kita hanya akan mengembalikan data utama di sini.
     * Header dan filter akan ditangani di registerEvents.
     */
    public function array(): array
    {
        $data = [];
        $rowNumber = 0;

        foreach ($this->results as $item) {
            $rowNumber++;

            // Logika Umur
            try {
                $birthDate = new \DateTime($item->patient->date_of_birth);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today);
                $umur = $age->y . ' Th ' . $age->m . ' Bln';
            } catch (\Exception $e) {
                $umur = '-';
            }

            // Logika Pasien Baru/Lama
            $isNewPatient = true;
            if ($item->patient && $item->patient->relationLoaded('registrations')) {
                if ($item->patient->registrations->count() > 1) {
                    $isNewPatient = false;
                }
            }

            $data[] = [
                $rowNumber,
                Carbon::parse($item->registration_date)->format('d-m-Y H:i'),
                $item->registration_number ?? '-',
                $isNewPatient ? ($item->patient->medical_record_number ?? '-') : '',
                !$isNewPatient ? ($item->patient->medical_record_number ?? '-') : '',
                $item->patient->name ?? '-',
                substr($item->patient->gender ?? '-', 0, 1),
                $umur,
                $item->patient->address ?? '-',
            ];
        }
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 18,
            'C' => 15,
            'D' => 12,
            'E' => 12,
            'F' => 35,
            'G' => 5,
            'H' => 15,
            'I' => 50,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header rows
                $headerRow1 = ['No', 'Tanggal Registrasi', 'No. Reg', 'No. RM', null, 'Nama Pasien', 'JK', 'Umur / Tahun', 'Alamat'];
                $headerRow2 = [null, null, null, 'Baru', 'Lama', null, null, null, null];

                // Siapkan detail filter untuk 7 baris pertama
                $filterData = [
                    ['LAPORAN POLIKLINIK', null, null, null, null, null, null, null, null], // Judul
                    ['PERIODE', Carbon::createFromFormat('d-m-Y', $this->filter['stgl1'])->format('d-m-Y') . ' s/d ' . Carbon::createFromFormat('d-m-Y', $this->filter['stgl2'])->format('d-m-Y'), null, null, null, null, null, null, null],
                    ['POLIKLINIK', $this->poliklinik->name ?? 'Semua Poli', null, null, null, null, null, null, null],
                    ['DOKTER', $this->dokter->fullname ?? 'Semua Dokter', null, null, null, null, null, null, null],
                    ['PENJAMIN', $this->penjamin->nama_perusahaan ?? 'Semua Penjamin', null, null, null, null, null, null, null],
                    // Tambah baris kosong untuk spasi/batas
                    [null, null, null, null, null, null, null, null, null],
                    [null, null, null, null, null, null, null, null, null],
                ];

                // Sisipkan baris di atas untuk filter dan header
                $sheet->insertNewRowBefore(1, 9);

                // Tulis filter di baris 1-7
                $sheet->fromArray($filterData, null, 'A1');
                // Tebalkan baris 1 dan 2-5 judul filter, sel B pada baris 2-5
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('A2:A5')->getFont()->setBold(true);
                $sheet->getStyle('B2:B5')->getFont()->setBold(true);

                // Merge judul di A1 sampai I1
                $sheet->mergeCells('A1:I1');

                // Merge filter baris label di kolom A, dan isian di kolom B
                foreach (range(2, 5) as $row) {
                    $sheet->mergeCells('A' . $row . ':A' . $row);
                    $sheet->mergeCells('B' . $row . ':I' . $row);
                }

                // Tulis data header pada baris 8 & 9
                $sheet->fromArray($headerRow1, null, 'A8');
                $sheet->fromArray($headerRow2, null, 'A9');

                // Merge Cells untuk Header
                $sheet->mergeCells('D8:E8'); // No. RM
                foreach (['A', 'B', 'C', 'F', 'G', 'H', 'I'] as $col) {
                    $sheet->mergeCells("{$col}8:{$col}9");
                }

                // Styling Header
                $headerRange = 'A8:I9';
                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                $sheet->getStyle($headerRange)->applyFromArray($headerStyle);

                // Styling Data & Borders
                $dataRowCount = count($this->results);
                if ($dataRowCount > 0) {
                    $dataRange = 'A10:I' . (9 + $dataRowCount);
                    $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    // Text alignment untuk data
                    $sheet->getStyle('A10:E' . (9 + $dataRowCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('G10:G' . (9 + $dataRowCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                }

                $sheet->setShowGridlines(false);
            },
        ];
    }
}
