<?php

namespace App\Exports;

use App\Models\WarehouseBarangNonFarmasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangNonFarmasiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load semua relasi yang dibutuhkan untuk optimasi
        return WarehouseBarangNonFarmasi::with([
            'satuan',
            'kategori',
            'golongan',
            'kelompok',
            'satuan_tambahan'
        ])->get();
    }

    /**
     * Mendefinisikan header kolom di file Excel.
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
            'Harga Beli',
            'PPN (%)',
            'HNA + PPN',
            'Status Aktif',
            'Jual Pasien',
            'Satuan Tambahan',
        ];
    }

    /**
     * Memetakan data dari setiap baris model ke kolom Excel.
     * @var WarehouseBarangNonFarmasi $barang
     */
    public function map($barang): array
    {
        // Kalkulasi HNA + PPN
        $hna_ppn = $barang->hna * (1 + ($barang->ppn / 100));

        // Gabungkan satuan tambahan jika ada
        $satuan_tambahan = $barang->satuan_tambahan->map(function ($item) {
            return ($item->satuan->nama ?? '') . ' (' . $item->isi . ')';
        })->implode(', ');

        return [
            $barang->id,
            $barang->nama,
            $barang->satuan->nama ?? 'N/A',
            $barang->kategori->nama ?? 'N/A',
            $barang->kelompok->nama ?? 'N/A',
            $barang->golongan->nama ?? 'N/A',
            $barang->hna,
            $barang->ppn,
            $hna_ppn,
            $barang->aktif ? 'Aktif' : 'Tidak Aktif',
            $barang->jual_pasien ? 'Ya' : 'Tidak',
            $satuan_tambahan,
        ];
    }
}
