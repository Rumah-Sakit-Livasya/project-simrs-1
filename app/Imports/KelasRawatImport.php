<?php

namespace App\Imports;

use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\TarifKelasRawat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KelasRawatImport implements ToModel, WithHeadingRow
{
    private $groupPenjamins;

    public function __construct()
    {
        // Ambil semua group penjamin dan buat map dengan key yang sudah di-slug
        // e.g., "Tarif BPJS Kesehatan" -> "tarif_bpjs_kesehatan"
        $this->groupPenjamins = GroupPenjamin::all()->keyBy(function ($item) {
            return Str::slug('Tarif ' . $item->name, '_');
        });
    }

    public function model(array $row)
    {
        try {
            // 1. Buat atau update data KelasRawat
            $kelasRawat = KelasRawat::updateOrCreate(
                ['kelas' => $row['kelas']],
                ['keterangan' => $row['keterangan']]
            );

            // 2. Loop melalui semua group penjamin yang kita punya
            foreach ($this->groupPenjamins as $slug => $group) {
                // Cek apakah ada kolom tarif untuk group ini di file Excel
                if (isset($row[$slug])) {
                    // 3. Buat atau update data TarifKelasRawat
                    TarifKelasRawat::updateOrCreate(
                        [
                            'kelas_rawat_id'    => $kelasRawat->id,
                            'group_penjamin_id' => $group->id,
                        ],
                        [
                            'tarif' => $row[$slug]
                        ]
                    );
                }
            }

            // Karena ToModel butuh return model, kita return object yang baru dibuat/diupdate.
            // Namun, data utama sudah masuk ke DB di atas.
            return $kelasRawat;
        } catch (\Throwable $e) {
            Log::error('KelasRawatImport error: ' . $e->getMessage(), [
                'row' => $row,
                'exception' => $e,
            ]);
            // Optional: return null or throw again, depending on your import needs
            return null;
        }
    }
}
