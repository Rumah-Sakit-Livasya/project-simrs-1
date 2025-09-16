<?php

namespace App\Imports;

// Tidak perlu lagi 'use App\Models\SIMRS\KelasRawat;' karena kita hanya memproses dari Excel
use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\TipeLaboratorium;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\BeforeSheet;

class LaboratoriumTarifImport implements ToCollection, WithStartRow, WithEvents
{
    private $grupPenjaminId;
    private $kelasRawatMap = [];

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->grupPenjaminId = $sheet->getCell('A2')->getValue();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                for ($col = 6; $col <= $highestColumnIndex; $col += 3) {
                    $cellValue = $sheet->getCellByColumnAndRow($col, 4)->getValue();
                    if ($cellValue && strpos($cellValue, ':') !== false) {
                        list($name, $id) = explode(':', $cellValue);
                        // Menggunakan ID sebagai key untuk pencarian yang lebih cepat
                        $this->kelasRawatMap[$id] = [
                            'name' => trim(strtoupper($name)),
                            'start_column_index' => $col - 1
                        ];
                    }
                }
            },
        ];
    }

    public function startRow(): int
    {
        return 6;
    }

    public function collection(Collection $rows)
    {
        $lastParameter = ParameterLaboratorium::withTrashed()->where('kode', 'LIKE', 'LAB%')->orderBy('id', 'desc')->first();
        $lastCodeNumber = 0;
        if ($lastParameter && preg_match('/(\d+)$/', $lastParameter->kode, $matches)) {
            $lastCodeNumber = (int) $matches[1];
        }

        Log::info('=====================================================');
        Log::info('MEMULAI PROSES IMPORT TARIF LABORATORIUM (LOGIKA UPDATE/CREATE)');
        Log::info("Nomor kode terakhir: {$lastCodeNumber} | Grup Penjamin ID: {$this->grupPenjaminId}");
        Log::info('=====================================================');

        DB::transaction(function () use ($rows, &$lastCodeNumber) {
            foreach ($rows as $rowIndex => $row) {
                if (empty($row[3])) continue;

                $idFromCell   = $row[0];
                $groupName    = $row[1];
                $tipeName     = $row[2];
                $parameterName = $row[3];
                $kategoriName = $row[4];

                $grup = GrupParameterLaboratorium::firstOrCreate(['nama_grup' => $groupName]);
                $kategori = KategoriLaboratorium::firstOrCreate(['nama_kategori' => $kategoriName]);
                $tipe = TipeLaboratorium::firstOrCreate(['nama_tipe' => $tipeName]);
                $parameter = null;

                if (!empty($idFromCell)) {
                    $parameter = ParameterLaboratorium::find($idFromCell);
                    if ($parameter) {
                        $parameter->update([
                            'grup_parameter_laboratorium_id' => $grup->id,
                            'kategori_laboratorium_id' => $kategori->id,
                            'tipe_laboratorium_id' => $tipe->id,
                            'parameter' => $parameterName,
                        ]);
                    } else {
                        Log::warning("--> PERINGATAN: Parameter dengan ID '{$idFromCell}' tidak ditemukan. Baris ini DILEWATI.");
                        continue;
                    }
                } else {
                    $lastCodeNumber++;
                    $newCode = 'LAB' . str_pad($lastCodeNumber, 3, '0', STR_PAD_LEFT);
                    $parameter = ParameterLaboratorium::firstOrCreate(
                        ['parameter' => $parameterName, 'grup_parameter_laboratorium_id' => $grup->id],
                        [
                            'kode' => $newCode,
                            'kategori_laboratorium_id' => $kategori->id,
                            'tipe_laboratorium_id' => $tipe->id,
                            'is_order' => 1,
                        ]
                    );
                }

                if ($parameter) {
                    // =================================================================
                    // PERUBAHAN UTAMA: Ganti logika delete/create menjadi updateOrCreate
                    // =================================================================

                    // Loop hanya melalui kelas rawat yang ADA DI FILE EXCEL
                    foreach ($this->kelasRawatMap as $kelasId => $kelasData) {
                        $col = $kelasData['start_column_index'];
                        $nilai_tarif = $row[$col + 4] ?? null;

                        // Proses hanya jika ada nilai di sel tarif (bisa 0, tapi tidak boleh kosong)
                        if ($nilai_tarif !== null && $nilai_tarif !== '') {
                            TarifParameterLaboratorium::updateOrCreate(
                                // Kriteria untuk MENCARI record
                                [
                                    'parameter_laboratorium_id' => $parameter->id,
                                    'group_penjamin_id'         => $this->grupPenjaminId,
                                    'kelas_rawat_id'            => $kelasId,
                                ],
                                // Data untuk di-UPDATE atau di-CREATE
                                [
                                    'share_dr'  => (float)($row[$col] ?? 0),
                                    'share_rs'  => (float)($row[$col + 1] ?? 0),
                                    'prasarana' => (float)($row[$col + 2] ?? 0),
                                    'bhp'       => (float)($row[$col + 3] ?? 0),
                                    'total'     => (float)$nilai_tarif,
                                ]
                            );
                        }
                    }
                }
            }
        });

        Log::info('PROSES IMPORT SELESAI.');
        Log::info('=====================================================' . PHP_EOL);
    }
}
