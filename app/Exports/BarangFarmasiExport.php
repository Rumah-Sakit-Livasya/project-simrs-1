<?php

namespace App\Exports;

use App\Models\WarehouseBarangFarmasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangFarmasiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load semua relasi yang dibutuhkan untuk optimasi
        return WarehouseBarangFarmasi::with([
            'satuan',
            'kategori',
            'golongan',
            'kelompok',
            'pabrik', // Relasi untuk principal
            'zat_aktif.zat' // Relasi nested untuk mendapatkan nama zat aktif
        ])->get();
    }

    /**
     * Mendefinisikan header kolom di file Excel sesuai gambar.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID#',
            'Nama Barang',
            'Satuan',
            'Kategori',
            'Kelompok',
            'Golongan',
            'Tipe', // Formularium Nasional
            'Harga Beli',
            'PPN Beli (%)',
            'HNA + PPN', // Kolom kalkulasi
            'Principal',
            'Harga Principal',
            'Diskon Principal (%)',
            'Zat Aktif',
            'Jenis Obat',
            'Formularium RS', // Formularium Rumah Sakit
            'PPN Jual Rawat Jalan (%)',
            'PPN Jual Rawat Inap (%)',
        ];
    }

    /**
     * Memetakan data dari setiap baris model ke kolom Excel.
     * @var WarehouseBarangFarmasi $barang
     */
    public function map($barang): array
    {
        // Kalkulasi HNA + PPN
        $hna_ppn = $barang->hna * (1 + ($barang->ppn / 100));

        // Menggabungkan nama zat aktif menjadi satu string
        $zat_aktif_string = $barang->zat_aktif->map(function ($item) {
            return $item->zat->nama ?? '';
        })->implode(', ');

        return [
            $barang->id,
            $barang->nama,
            $barang->satuan->nama ?? 'N/A',
            $barang->kategori->nama ?? 'N/A',
            $barang->kelompok->nama ?? 'N/A',
            $barang->golongan->nama ?? 'N/A',
            $barang->tipe, // FN atau NFN
            $barang->hna,
            $barang->ppn,
            $hna_ppn,
            $barang->pabrik->nama ?? 'N/A', // Nama dari relasi 'pabrik'
            $barang->harga_principal,
            $barang->diskon_principal,
            $zat_aktif_string,
            $barang->jenis_obat, // paten atau generik
            $barang->formularium, // RS atau NRS
            $barang->ppn_rajal,
            $barang->ppn_ranap,
        ];
    }
}
