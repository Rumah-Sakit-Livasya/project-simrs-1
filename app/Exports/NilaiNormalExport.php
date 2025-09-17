<?php

namespace App\Exports;

use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NilaiNormalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data nilai normal beserta relasi parameter laboratorium
        return NilaiNormalLaboratorium::with('parameter_laboratorium')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Mendefinisikan judul kolom pada file Excel
        return [
            'kode_parameter',
            'nama_parameter',
            'jenis_kelamin', // Laki-laki, Perempuan, Semua
            'dari_tahun',
            'dari_bulan',
            'dari_hari',
            'sampai_tahun',
            'sampai_bulan',
            'sampai_hari',
            'min',
            'max',
            'nilai_normal_text', // Contoh: 10.5 - 14.0
            'keterangan',
            'min_kritis',
            'max_kritis',
        ];
    }

    /**
     * @var NilaiNormalLaboratorium $nilaiNormal
     */
    public function map($nilaiNormal): array
    {
        // Memecah umur menjadi tahun, bulan, hari untuk setiap baris data
        $dariUmur = explode('-', $nilaiNormal->dari_umur);
        $sampaiUmur = explode('-', $nilaiNormal->sampai_umur);

        return [
            $nilaiNormal->parameter_laboratorium->kode ?? 'KODE_TIDAK_DITEMUKAN',
            $nilaiNormal->parameter_laboratorium->parameter ?? 'PARAMETER_TIDAK_DITEMUKAN',
            $nilaiNormal->jenis_kelamin,
            $dariUmur[0] ?? 0, // dari_tahun
            $dariUmur[1] ?? 0, // dari_bulan
            $dariUmur[2] ?? 0, // dari_hari
            $sampaiUmur[0] ?? 0, // sampai_tahun
            $sampaiUmur[1] ?? 0, // sampai_bulan
            $sampaiUmur[2] ?? 0, // sampai_hari
            $nilaiNormal->min,
            $nilaiNormal->max,
            $nilaiNormal->nilai_normal,
            $nilaiNormal->keterangan,
            $nilaiNormal->min_kritis,
            $nilaiNormal->max_kritis,
        ];
    }
}
