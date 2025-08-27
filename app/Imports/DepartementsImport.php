<?php

namespace App\Imports;

use App\Models\SIMRS\Departement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header kolom

class DepartementsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Fungsi ini akan dipanggil untuk setiap baris di file Excel.
        // Variabel $row adalah array asosiatif di mana key-nya adalah nama header kolom (karena kita menggunakan WithHeadingRow).

        // Lakukan pengecekan untuk memastikan data esensial tidak kosong
        if (!isset($row['name']) || !isset($row['kode'])) {
            return null; // Lewati baris ini jika nama atau kode kosong
        }

        return new Departement([
            'name'                      => $row['name'],
            'kode'                      => $row['kode'],
            'keterangan'                => $row['keterangan'],
            'quota'                     => $row['quota'] ?? null,
            'kode_poli'                 => $row['kode_poli'] ?? null,
            'publish_online'            => $row['publish_online'] ?? null,
            'revenue_and_cost_center'   => $row['revenue_and_cost_center'] ?? null,
            'master_layanan_rl'         => $row['master_layanan_rl'] ?? null,
        ]);
    }
}
