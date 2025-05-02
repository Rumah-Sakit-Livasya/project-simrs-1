<?php

namespace App\Exports;

use App\Models\LaporanInternal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanHarianExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tanggal;
    protected $jenis;

    public function __construct($tanggal, $jenis = null)
    {
        $this->tanggal = $tanggal;
        $this->jenis = $jenis;
    }

    public function collection()
    {
        $query = LaporanInternal::with(['user', 'organization'])
            ->whereDate('tanggal', $this->tanggal);

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        return $query->orderBy('jam_masuk')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis',
            'User',
            'Organisasi',
            'Kegiatan/Kendala',
            'Status',
            'Jam Masuk',
            'Jam Diproses',
            'Jam Selesai',
            'Respon Time',
            'Keterangan'
        ];
    }

    public function map($laporan): array
    {
        return [
            $laporan->id,
            $laporan->tanggal,
            $laporan->jenis == 'kegiatan' ? 'Kegiatan' : 'Kendala',
            $laporan->user->name,
            $laporan->organization->name,
            $laporan->kegiatan,
            $laporan->status,
            $laporan->jam_masuk,
            $laporan->jam_diproses,
            $laporan->jam_selesai,
            $this->calculateResponTime($laporan),
            $laporan->keterangan
        ];
    }

    protected function calculateResponTime($laporan)
    {
        if ($laporan->jam_masuk && $laporan->jam_diproses) {
            $start = \Carbon\Carbon::parse($laporan->jam_masuk);
            $end = \Carbon\Carbon::parse($laporan->jam_diproses);
            return $start->diff($end)->format('%H:%I:%S');
        }
        return '-';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],

            // Set border for all cells
            'A1:L' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
