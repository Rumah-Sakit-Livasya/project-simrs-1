<?php

namespace App\Imports;

use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // <-- INI SUDAH DI POSISI YANG BENAR
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\BeforeSheet;

class LaboratoriumTarifImport implements ToCollection, WithStartRow, WithEvents
{
    private $grupPenjaminId;
    private $kelasRawatMap = [];

    /**
     * Membaca header untuk memetakan kolom sebelum memproses baris data.
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->grupPenjaminId = $sheet->getCell('A2')->getValue();

                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                for ($col = 5; $col <= $highestColumnIndex; $col += 3) { // Melompat 3 kolom
                    $cellValue = $sheet->getCellByColumnAndRow($col, 4)->getValue();
                    if ($cellValue && strpos($cellValue, ':') !== false) {
                        list($name, $id) = explode(':', $cellValue);
                        $this->kelasRawatMap[] = [
                            'id' => $id,
                            'name' => trim(strtoupper($name)),
                            'start_column_index' => $col - 1
                        ];
                    }
                }
            },
        ];
    }

    /**
     * Baris data dimulai dari baris ke-6.
     */
    public function startRow(): int
    {
        return 6;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $lastParameter = ParameterLaboratorium::withTrashed()
            ->where('kode', 'LIKE', 'RAD%')
            ->orderBy('id', 'desc')
            ->first();

        $lastCodeNumber = 0;
        if ($lastParameter && preg_match('/(\d+)$/', $lastParameter->kode, $matches)) {
            $lastCodeNumber = (int) $matches[1];
        }

        Log::info('=====================================================');
        Log::info('MEMULAI PROSES IMPORT (LOGIKA FINAL: ID vs KODE)');
        Log::info("Nomor kode terakhir: {$lastCodeNumber} | Grup Penjamin ID: {$this->grupPenjaminId}");
        Log::info('=====================================================');

        DB::transaction(function () use ($rows, &$lastCodeNumber) {
            foreach ($rows as $rowIndex => $row) {
                if (empty($row[2])) continue; // Lewati jika nama parameter kosong

                $idFromCell   = $row[0];
                $groupName    = $row[1];
                $parameterName = $row[2];
                $kategoriName = $row[3];

                $grup = GrupParameterLaboratorium::firstOrCreate(['nama_grup' => $groupName], ['no_urut' => 0]);
                $kategori = KategoriLaboratorium::firstOrCreate(['nama_kategori' => $kategoriName], ['status' => 1]);

                $parameter = null;

                // =================================================================
                // LOGIKA UTAMA YANG SEKARANG BENAR-BENAR MEMISAHKAN ID DAN KODE
                // =================================================================
                if (!empty($idFromCell)) {
                    // KASUS 1: ID DIISI -> HANYA UPDATE BERDASARKAN PRIMARY KEY
                    Log::info("--- [ BARIS #" . ($rowIndex + 6) . " ] --- ID diisi: {$idFromCell}. Mencari untuk UPDATE...");

                    // Cari parameter berdasarkan PRIMARY KEY (id)
                    $parameter = ParameterLaboratorium::find($idFromCell);

                    if ($parameter) {
                        // DITEMUKAN: Lakukan UPDATE
                        Log::info("--> Ditemukan. Mengupdate '{$parameter->parameter}' menjadi '{$parameterName}'.");
                        $parameter->update([
                            'grup_parameter_laboratorium_id' => $grup->id,
                            'kategori_laboratorium_id' => $kategori->id,
                            'parameter' => $parameterName,
                        ]);
                    } else {
                        // TIDAK DITEMUKAN: Lewati dan beri peringatan
                        Log::warning("--> PERINGATAN: Parameter dengan ID '{$idFromCell}' tidak ditemukan. Baris ini DILEWATI.");
                        continue; // PENTING: Langsung ke baris berikutnya
                    }
                } else {
                    // KASUS 2: ID KOSONG -> HANYA CREATE DATA BARU
                    $lastCodeNumber++;
                    $newCode = 'RAD' . str_pad($lastCodeNumber, 3, '0', STR_PAD_LEFT);
                    Log::info("--- [ BARIS #" . ($rowIndex + 6) . " ] --- ID kosong. Membuat parameter baru '{$parameterName}' dengan kode: {$newCode}");

                    // Gunakan firstOrCreate untuk mencegah duplikat dalam file yang sama
                    $parameter = ParameterLaboratorium::firstOrCreate(
                        ['parameter' => $parameterName, 'grup_parameter_laboratorium_id' => $grup->id],
                        [
                            'kode' => $newCode, // Kode baru yang di-generate
                            'kategori_laboratorium_id' => $kategori->id,
                        ]
                    );
                }

                // Proses tarif (logika ini sudah benar)
                // Hapus tarif lama untuk sinkronisasi, lalu buat yang baru dari file
                if ($parameter) {
                    TarifParameterLaboratorium::where('parameter_laboratorium_id', $parameter->id)
                        ->where('group_penjamin_id', $this->grupPenjaminId)
                        ->delete();

                    foreach ($this->kelasRawatMap as $kelas) {
                        $col = $kelas['start_column_index'];
                        $nilai_tarif = $row[$col + 2] ?? null;

                        if ($nilai_tarif !== null && $nilai_tarif !== '' && (float)$nilai_tarif > 0) {
                            TarifParameterLaboratorium::create([
                                'parameter_laboratorium_id' => $parameter->id,
                                'group_penjamin_id'      => $this->grupPenjaminId,
                                'kelas_rawat_id'         => $kelas['id'],
                                'share_dr' => (float)($row[$col] ?? 0),
                                'share_rs'     => (float)($row[$col + 1] ?? 0),
                                'total'        => (float)$nilai_tarif,
                            ]);
                        }
                    }
                }
            }
        });

        Log::info('PROSES IMPORT SELESAI.');
        Log::info('=====================================================' . PHP_EOL);
    }
}
