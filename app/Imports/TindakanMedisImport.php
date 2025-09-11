<?php

namespace App\Imports;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GrupTindakanMedis;
use App\Models\SIMRS\TindakanMedis;
use App\Models\TarifTindakanMedis;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class TindakanMedisImport implements ToCollection, WithStartRow, WithEvents
{
    private $grupPenjaminId;
    private $departementId;
    private $kelasRawatMap = [];

    /**
     * Daftarkan event untuk membaca header sebelum memproses data.
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $this->grupPenjaminId = $sheet->getCell('A2')->getValue();
                $this->departementId = $sheet->getCell('C2')->getValue();

                // =================================================================
                // AWAL DARI LOGIKA PARSING HEADER YANG BARU DAN BENAR
                // =================================================================
                $highestColumn = $sheet->getHighestColumn(); // Dapatkan kolom terakhir, misal: 'CZ'
                // Ubah huruf kolom menjadi angka, misal 'F' menjadi 6. Indeks dimulai dari 1.
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                // Kita tahu header kelas rawat ada di baris 4
                $headerRow = 4;
                // Kita tahu tarif dimulai dari kolom F (indeks 6 jika dimulai dari 1)
                $startColumnIndex = 6;

                // Gunakan loop for standar yang melompat 5 kolom setiap kali
                for ($col = $startColumnIndex; $col <= $highestColumnIndex; $col += 5) {
                    // Dapatkan nilai dari sel di baris header pada kolom saat ini
                    $cellValue = $sheet->getCellByColumnAndRow($col, $headerRow)->getValue();

                    // Hanya proses jika sel tersebut berisi nama kelas rawat (mengandung ':')
                    if ($cellValue && strpos($cellValue, ':') !== false) {
                        list($name, $id) = explode(':', $cellValue);

                        // Simpan ke peta. Indeks kolom di sini berbasis 1,
                        // sementara kode 'collection' kita menggunakan berbasis 0. Jadi kita kurangi 1.
                        $this->kelasRawatMap[] = [
                            'id' => $id,
                            'name' => trim(strtoupper($name)),
                            // Simpan indeks berbasis 0 untuk konsistensi dengan method collection()
                            'start_column_index' => $col - 1
                        ];
                    }
                }
                // =================================================================
                // AKHIR DARI LOGIKA PARSING HEADER YANG BARU
                // =================================================================
            },
        ];
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $departement = Departement::find($this->departementId);
        if (!$departement) {
            throw new \Exception("Import dihentikan. Departemen dengan ID '{$this->departementId}' tidak ditemukan di database.");
        }

        Log::info('=====================================================');
        Log::info('MEMULAI PROSES IMPORT DENGAN LOGIKA UPDATE-OR-CREATE');
        Log::info("Departemen: {$departement->name} | Grup Penjamin ID: {$this->grupPenjaminId}");
        Log::info('=====================================================');

        DB::transaction(function () use ($rows, $departement) {
            foreach ($rows as $rowIndex => $row) {
                if (empty($row[1]) && empty($row[2])) continue;

                $kode = $row[1];
                $name = $row[2];
                Log::info("--- [ BARIS EXCEL #" . ($rowIndex + $this->startRow()) . " ] --- Tindakan: {$name} ({$kode})");

                $groupName = $row[3];
                $isDeleted = strtolower($row[4] ?? 'f') === 't';

                $group = GrupTindakanMedis::firstOrCreate(
                    ['nama_grup' => $groupName, 'departement_id' => $departement->id],
                    ['departement_id' => $departement->id, 'status' => 1]
                );

                $tindakan = TindakanMedis::withTrashed()->updateOrCreate(
                    ['kode' => $kode],
                    [
                        'nama_tindakan' => $name,
                        'nama_billing'  => $name, // <-- Kolom wajib 'nama_billing' sekarang diisi
                        'grup_tindakan_medis_id' => $group->id,
                    ]
                );

                if ($isDeleted) {
                    if (!$tindakan->trashed()) $tindakan->delete();
                    continue;
                } else {
                    if ($tindakan->trashed()) $tindakan->restore();
                }

                // =================================================================
                // AWAL DARI LOGIKA BARU: UPDATE ATAU BUAT
                // =================================================================
                foreach ($this->kelasRawatMap as $kelas) {
                    $col = $kelas['start_column_index'];

                    // Ambil nilai TARIF sebagai KUNCI
                    $nilai_tarif_total = $row[$col + 4] ?? null;

                    // Tentukan kunci unik untuk mencari tarif yang sudah ada
                    $uniqueKeys = [
                        'tindakan_medis_id' => $tindakan->id,
                        'kelas_rawat_id'    => $kelas['id'],
                        'group_penjamin_id' => $this->grupPenjaminId,
                    ];

                    // KONDISI 1: Jika kolom TARIF diisi dengan angka positif
                    if ($nilai_tarif_total !== null && $nilai_tarif_total !== '' && (float)$nilai_tarif_total > 0) {

                        Log::info("--> Kelas '{$kelas['name']}': Ditemukan data tarif. Menjalankan UPDATE atau CREATE...");

                        // Siapkan data nilai yang akan diupdate atau dibuat
                        $valuesToUpdate = [
                            'share_dr'  => (float) ($row[$col] ?? 0),
                            'share_rs'  => (float) ($row[$col + 1] ?? 0),
                            'prasarana' => (float) ($row[$col + 2] ?? 0),
                            'bhp'       => (float) ($row[$col + 3] ?? 0),
                            'total'     => (float) $nilai_tarif_total,
                        ];

                        // Gunakan updateOrCreate:
                        // - Jika record dengan $uniqueKeys ditemukan, akan di-update dengan $valuesToUpdate.
                        // - Jika tidak ditemukan, akan dibuat record baru yang menggabungkan $uniqueKeys dan $valuesToUpdate.
                        TarifTindakanMedis::updateOrCreate($uniqueKeys, $valuesToUpdate);
                    } else {
                        // KONDISI 2: Jika kolom TARIF kosong atau nol
                        Log::info("--> Kelas '{$kelas['name']}': Kolom TARIF kosong atau nol. Mencari data lama untuk dihapus...");

                        // Kita tidak ingin membuat atau mengupdate, tetapi kita ingin MENGHAPUS
                        // jika sebelumnya ada tarif untuk kelas ini. Ini untuk menangani kasus
                        // di mana Anda menghapus tarif dari file Excel dan ingin itu terhapus juga di database.
                        TarifTindakanMedis::where($uniqueKeys)->delete();
                        Log::info("--> Data lama (jika ada) untuk kelas ini telah dihapus.");
                    }
                }
            }
        });

        Log::info('PROSES IMPORT SELESAI.');
        Log::info('=====================================================' . PHP_EOL);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 6;
    }
}
