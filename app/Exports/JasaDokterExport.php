<?php

namespace App\Exports;

use App\Models\keuangan\JasaDokter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; // Untuk styling
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk lebar kolom otomatis (opsional)
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Untuk manipulasi worksheet
use PhpOffice\PhpSpreadsheet\Style\Fill; // Untuk warna fill
use PhpOffice\PhpSpreadsheet\Style\Border; // Untuk border
use PhpOffice\PhpSpreadsheet\Style\Font; // Untuk font
use Carbon\Carbon;

class JasaDokterExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $jasaDokterItems;

    public function __construct($jasaDokterItems)
    {
        $this->jasaDokterItems = $jasaDokterItems;
    }

    public function collection()
    {
        return $this->jasaDokterItems;
    }

    public function headings(): array
    {
        return [
            'No. AP',                       // A
            'Tgl AP',                       // B
            'No. RM',                       // C
            'No. Reg',                      // D - Perlu lebih panjang
            'Nama Pasien',                  // E - Perlu lebih panjang
            'Tgl Bill',                     // F
            'Detail Tagihan (AP)',          // G - Perlu lebih panjang
            'Dokter AP',                    // H - Perlu lebih panjang
            'Penjamin (Pasien)',            // I
            'Nominal AP (Bruto)',           // J
            'Diskon AP',                    // K
            'PPN AP (%)',                   // L
            'JKP AP',                       // M
            'Jasa Dokter AP (Netto)',       // N
            'Status AP',                    // O
            'Dokter Registrasi',            // P - Perlu lebih panjang
        ];
    }

    public function map($jasaDokter): array
    {
        return [
            $jasaDokter->ap_number ?? '-',
            $jasaDokter->ap_date ? Carbon::parse($jasaDokter->ap_date)->format('d-m-Y') : '-',
            $jasaDokter->tagihanPasien?->registration?->patient?->medical_record_number ?? '-',
            $jasaDokter->tagihanPasien?->registration?->registration_number ?? '-', // D
            $jasaDokter->tagihanPasien?->registration?->patient?->name ?? '-', // E
            $jasaDokter->bill_date ? Carbon::parse($jasaDokter->bill_date)->format('d-m-Y') : (optional($jasaDokter->tagihanPasien?->bilinganSatu?->created_at)->format('d-m-Y') ?? '-'),
            $jasaDokter->nama_tindakan ?? '-', // G
            optional($jasaDokter->dokter?->employee)->fullname ?? optional($jasaDokter->dokter)->nama_dokter ?? '-', // H
            $jasaDokter->tagihanPasien?->registration?->penjamin?->nama_perusahaan ?? '-',
            $jasaDokter->nominal ?? 0,
            $jasaDokter->diskon ?? 0,
            $jasaDokter->ppn_persen ?? 0,
            $jasaDokter->jkp ?? 0,
            $jasaDokter->jasa_dokter ?? 0,
            ucfirst($jasaDokter->status ?? 'draft'),
            optional($jasaDokter->tagihanPasien?->registration?->doctor?->employee)->fullname ?? '-', // P
        ];
    }

    /**
     * Menerapkan styling pada worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Style untuk Header (Baris 1)
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Warna teks putih
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
        // Atur tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(20);


        // 2. Mengatur Lebar Kolom Spesifik
        // Kolom D: No. Reg
        $sheet->getColumnDimension('D')->setWidth(20); // Sesuaikan lebarnya
        // Kolom E: Nama Pasien
        $sheet->getColumnDimension('E')->setWidth(30); // Sesuaikan lebarnya
        // Kolom G: Detail Tagihan (AP)
        $sheet->getColumnDimension('G')->setWidth(40); // Sesuaikan lebarnya
        // Kolom H: Dokter AP
        $sheet->getColumnDimension('H')->setWidth(25); // Sesuaikan lebarnya
        // Kolom P: Dokter Registrasi
        $sheet->getColumnDimension('P')->setWidth(25); // Sesuaikan lebarnya


        // 3. (Opsional) Style untuk Sel Data (Baris 2 dan seterusnya)
        $lastRow = $this->jasaDokterItems->count() + 1; // +1 karena ada header
        if ($lastRow > 1) {
            $sheet->getStyle('A2:P' . $lastRow)->applyFromArray([ // Sesuaikan range kolom P jika ada perubahan
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFBFBFBF'], // Warna border lebih soft
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Format angka untuk kolom numerik (J hingga N)
            $sheet->getStyle('J2:N' . $lastRow)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); // Format #,##0

            // Rata kiri untuk beberapa kolom teks
            $textColumns = ['A', 'C', 'D', 'E', 'G', 'H', 'I', 'O', 'P'];
            foreach ($textColumns as $col) {
                $sheet->getStyle($col . '2:' . $col . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
            // Rata tengah untuk beberapa kolom
            $centerColumns = ['B', 'F'];
            foreach ($centerColumns as $col) {
                $sheet->getStyle($col . '2:' . $col . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }


        $sheet->freezePane('A2');


        // 5. (Opsional) Auto Filter pada Header
        $sheet->setAutoFilter('A1:P1'); // Sesuaikan range kolom P1

        return []; // Tidak perlu return array style jika sudah applyFromArray
    }
}
