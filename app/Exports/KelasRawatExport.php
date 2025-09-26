<?php

namespace App\Exports;

use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\GroupPenjamin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KelasRawatExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groupPenjamins;

    public function __construct()
    {
        // Ambil semua group penjamin sekali saja untuk efisiensi
        $this->groupPenjamins = GroupPenjamin::orderBy('id')->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load relasi untuk menghindari N+1 query problem
        return KelasRawat::with('tarifKelasRawat')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Header statis
        $headings = [
            'ID',
            'Kelas',
            'Keterangan',
        ];

        // Tambahkan header dinamis dari group penjamin
        foreach ($this->groupPenjamins as $group) {
            $headings[] = 'Tarif ' . $group->name; // e.g., "Tarif BPJS"
        }

        return $headings;
    }

    /**
     * @param KelasRawat $kelasRawat
     * @return array
     */
    public function map($kelasRawat): array
    {
        // Data statis untuk setiap baris
        $row = [
            $kelasRawat->id,
            $kelasRawat->kelas,
            $kelasRawat->keterangan,
        ];

        // Ambil tarif yang sudah di-eager load
        $tarifs = $kelasRawat->tarifKelasRawat->keyBy('group_penjamin_id');

        // Tambahkan data tarif dinamis
        foreach ($this->groupPenjamins as $group) {
            // Cek apakah ada tarif untuk group penjamin ini
            $tarif = $tarifs->get($group->id);
            // Jika ada, tambahkan nilainya, jika tidak, isi dengan 0
            $row[] = $tarif ? $tarif->tarif : 0;
        }

        return $row;
    }
}
