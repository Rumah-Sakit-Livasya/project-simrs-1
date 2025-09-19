<?php

namespace App\Imports;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Peralatan\TarifPeralatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class PeralatanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Ambil ID Grup Penjamin dari sel A2
        $groupPenjaminId = $rows[1][0] ?? null;
        if (!$groupPenjaminId || !GroupPenjamin::find($groupPenjaminId)) {
            throw new \Exception("ID Grup Penjamin tidak valid atau tidak ditemukan di sel A2.");
        }

        // Parsing header Kelas Rawat dari baris ke-4 untuk mendapatkan mapping kolom
        $kelasRawatHeaders = $rows[3];
        $kelasRawatMap = [];
        foreach ($kelasRawatHeaders as $columnIndex => $header) {
            if ($columnIndex >= 3 && !empty($header)) { // Mulai dari kolom D
                $parts = explode(':', $header);
                $kelasId = trim(end($parts));
                if (is_numeric($kelasId)) {
                    $kelasRawatMap[$columnIndex] = (int)$kelasId;
                }
            }
        }

        if (empty($kelasRawatMap)) {
            throw new \Exception("Tidak dapat mem-parsing header Kelas Rawat dari baris ke-4.");
        }

        DB::transaction(function () use ($rows, $groupPenjaminId, $kelasRawatMap) {
            // Loop melalui baris data, dimulai dari baris ke-6 (index 5)
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex < 5 || empty($row[1]) || empty($row[2])) {
                    continue; // Lewati baris header dan baris kosong
                }

                $kode = trim($row[2]);
                $namaFull = trim($row[1]);
                $satuanPakai = 'kali'; // Nilai default jika tidak ada pemisah

                if (Str::contains($namaFull, '/')) {
                    $namaParts = explode('/', $namaFull, 2);
                    $nama = trim($namaParts[0]);
                    $satuanPakai = trim($namaParts[1]);
                } else {
                    $nama = $namaFull;
                }

                // Buat atau perbarui data Peralatan
                $peralatan = Peralatan::updateOrCreate(
                    ['kode' => $kode],
                    ['nama' => $nama, 'satuan_pakai' => $satuanPakai]
                );

                // Buat atau perbarui Tarif untuk setiap Kelas Rawat
                foreach ($kelasRawatMap as $columnIndex => $kelasRawatId) {
                    $shareDr = $row[$columnIndex] ?? 0;
                    $shareRs = $row[$columnIndex + 1] ?? 0;
                    $total = $row[$columnIndex + 2] ?? 0;

                    if ($total > 0 || $shareDr > 0 || $shareRs > 0) {
                        TarifPeralatan::updateOrCreate(
                            [
                                'peralatan_id'      => $peralatan->id,
                                'group_penjamin_id' => $groupPenjaminId,
                                'kelas_rawat_id'    => $kelasRawatId,
                            ],
                            [
                                'share_dr' => $shareDr,
                                'share_rs' => $shareRs,
                                'total'    => $total,
                            ]
                        );
                    }
                }
            }
        });
    }
}
