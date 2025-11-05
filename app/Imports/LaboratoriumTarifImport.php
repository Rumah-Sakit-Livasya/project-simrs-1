<?php

namespace App\Imports;

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
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Events\BeforeSheet;

class LaboratoriumTarifImport implements ToCollection, WithStartRow, WithEvents, WithValidation, SkipsOnFailure
{
    private $grupPenjaminId;
    private $kelasRawatMap = [];
    public $failures = [];

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->grupPenjaminId = $sheet->getCell('A2')->getValue();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                for ($col = 6; $col <= $highestColumnIndex; $col += 5) { // Koreksi: Harusnya increment 5 (dr, rs, prasarana, bhp, total)
                    $cellValue = $sheet->getCellByColumnAndRow($col, 4)->getValue();
                    if ($cellValue && strpos($cellValue, ':') !== false) {
                        list($name, $id) = explode(':', $cellValue);
                        $this->kelasRawatMap[(int)$id] = [ // Cast ID ke integer
                            'name' => trim(strtoupper($name)),
                            'start_column_index' => $col - 1 // Index kolom 'share_dr' (dimulai dari 0)
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

    public function rules(): array
    {
        return [
            '3' => 'required|string', // Kolom ke-4 (indeks 3) yaitu 'parameter' wajib ada
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function collection(Collection $rows)
    {
        $lastParameter = ParameterLaboratorium::withTrashed()->where('kode', 'LIKE', 'LAB%')->orderBy('id', 'desc')->first();
        $lastCodeNumber = 0;
        if ($lastParameter && preg_match('/(\d+)$/', $lastParameter->kode, $matches)) {
            $lastCodeNumber = (int) $matches[1];
        }

        Log::info('=====================================================');
        Log::info('MEMULAI PROSES IMPORT TARIF LABORATORIUM');
        Log::info("Grup Penjamin ID: {$this->grupPenjaminId}");
        Log::info('=====================================================');

        foreach ($rows as $rowIndex => $row) {
            $currentRowNumber = $rowIndex + $this->startRow();
            $parameterNameForRow = $row[3] ?? 'N/A';

            try {
                // Proses setiap baris di dalam transaksi terpisah
                DB::transaction(function () use ($row, $currentRowNumber, &$lastCodeNumber, $parameterNameForRow) {
                    // Cukup satu validasi di awal
                    if (empty($row[3])) {
                        return; // Gunakan 'return' untuk keluar dari closure transaksi
                    }

                    $idFromCell    = $row[0];
                    $groupName     = $row[1];
                    $tipeName      = $row[2];
                    $parameterName = $row[3];
                    $kategoriName  = $row[4];

                    $grup = GrupParameterLaboratorium::firstOrCreate(['nama_grup' => $groupName]);
                    $kategori = KategoriLaboratorium::firstOrCreate(['nama_kategori' => $kategoriName]);
                    $tipe = TipeLaboratorium::firstOrCreate(['nama_tipe' => $tipeName]);
                    $parameter = null;

                    if (!empty($idFromCell)) {
                        $parameter = ParameterLaboratorium::find($idFromCell);
                        if (!$parameter) {
                            // Jika parameter tidak ditemukan, lempar exception untuk ditangkap di luar
                            throw new \Exception("Parameter dengan ID '{$idFromCell}' tidak ditemukan.");
                        }
                        Log::info("Baris {$currentRowNumber}: UPDATE mode. Parameter '{$parameter->parameter}' (ID: {$parameter->id}).");
                        $parameter->update([
                            'grup_parameter_laboratorium_id' => $grup->id,
                            'kategori_laboratorium_id' => $kategori->id,
                            'tipe_laboratorium_id' => $tipe->id,
                            'parameter' => $parameterName,
                        ]);
                    } else {
                        $lastCodeNumber++;
                        $newCode = 'LAB' . str_pad($lastCodeNumber, 3, '0', STR_PAD_LEFT);
                        $parameter = ParameterLaboratorium::create([
                            'parameter' => $parameterName,
                            'grup_parameter_laboratorium_id' => $grup->id,
                            'kode' => $newCode,
                            'kategori_laboratorium_id' => $kategori->id,
                            'tipe_laboratorium_id' => $tipe->id,
                            'is_order' => 1,
                        ]);
                        Log::info("Baris {$currentRowNumber}: CREATE mode. Parameter baru '{$parameter->parameter}' (ID: {$parameter->id}).");
                    }

                    // Loop untuk tarif (hanya jika parameter berhasil ditemukan atau dibuat)
                    foreach ($this->kelasRawatMap as $kelasId => $kelasData) {
                        $col = $kelasData['start_column_index'];

                        $share_dr  = $row[$col] ?? 0;
                        $share_rs  = $row[$col + 1] ?? 0;
                        $prasarana = $row[$col + 2] ?? 0;
                        $bhp       = $row[$col + 3] ?? 0;
                        $total     = $row[$col + 4] ?? null;

                        // Validasi tipe data numerik
                        if (!is_numeric($share_dr) || !is_numeric($share_rs) || !is_numeric($prasarana) || !is_numeric($bhp) || !is_numeric($total)) {
                            throw new \Exception("Data tarif tidak valid (non-numerik) pada kelas ID {$kelasId}.");
                        }

                        if ($total !== null && $total !== '') {
                            $tarif = TarifParameterLaboratorium::firstOrNew([
                                'parameter_laboratorium_id' => $parameter->id,
                                'group_penjamin_id'         => $this->grupPenjaminId,
                                'kelas_rawat_id'            => $kelasId,
                            ]);

                            $tarif->fill([
                                'share_dr'  => (float)$share_dr,
                                'share_rs'  => (float)$share_rs,
                                'prasarana' => (float)$prasarana,
                                'bhp'       => (float)$bhp,
                                'total'     => (float)$total,
                            ]);

                            if ($tarif->isDirty()) {
                                Log::info(" -> [Kelas ID: {$kelasId}] Menyimpan perubahan... Total baru: {$total}");
                                $tarif->save();
                            }
                        }
                    }
                });
            } catch (\Exception $e) {
                Log::error("!!! GAGAL MEMPROSES BARIS {$currentRowNumber} (Parameter: {$parameterNameForRow}) !!!");
                Log::error("-> Pesan Error: " . $e->getMessage());
                // Tambahkan error ke array failures untuk dilaporkan ke user
                $this->failures[] = new Failure($currentRowNumber, 'Processing Error', [$e->getMessage()], $row->toArray());
                // 'continue' di sini valid karena berada langsung di dalam 'foreach'
                continue;
            }
        }

        Log::info('PROSES IMPORT SELESAI.');
        Log::info('=====================================================' . PHP_EOL);
    }
}
