<?php

namespace App\Imports;

use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehousePabrik;
use App\Models\WarehouseZatAktif;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection; // Ubah dari ToModel ke ToCollection
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

// UBAH DARI ToModel MENJADI ToCollection
class BarangFarmasiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private Collection $satuanCache;
    private Collection $kategoriCache;
    private Collection $kelompokCache;
    private Collection $golonganCache;
    private Collection $pabrikCache;
    private Collection $zatAktifCache;
    private Collection $existingBarang;

    public function __construct()
    {
        // 1. Pre-load semua data relasi, ini sudah optimal
        $this->satuanCache = WarehouseSatuanBarang::pluck('id', 'nama');
        $this->kategoriCache = WarehouseKategoriBarang::pluck('id', 'nama');
        $this->kelompokCache = WarehouseKelompokBarang::pluck('id', 'nama');
        $this->golonganCache = WarehouseGolonganBarang::pluck('id', 'nama');
        $this->pabrikCache = WarehousePabrik::pluck('id', 'nama');
        $this->zatAktifCache = WarehouseZatAktif::pluck('id', 'kode');
    }

    // UBAH dari model() MENJADI collection()
    public function collection(Collection $rows)
    {
        // 2. Pre-load semua barang yang mungkin akan diupdate
        $namaBarangFromExcel = $rows->pluck('nama_barang')->filter()->unique();
        $this->existingBarang = WarehouseBarangFarmasi::whereIn('nama', $namaBarangFromExcel)->pluck('id', 'nama');

        $barangToInsert = [];
        $barangToUpdate = [];
        $zatAktifToSync = [];

        foreach ($rows as $row) {
            if (empty($row['nama_barang'])) {
                continue;
            }

            $trimmedNamaBarang = trim($row['nama_barang']);

            $satuanId = $this->getOrCreateRelationId($row['satuan'], WarehouseSatuanBarang::class, $this->satuanCache);
            $kategoriId = $this->getOrCreateRelationId($row['kategori'], WarehouseKategoriBarang::class, $this->kategoriCache);

            if (is_null($kategoriId) || is_null($satuanId)) {
                continue; // Lewati jika data wajib tidak valid
            }

            // Siapkan data untuk insert/update
            $barangData = [
                'nama' => $trimmedNamaBarang,
                'kode' => Str::slug($trimmedNamaBarang),
                'satuan_id' => $satuanId,
                'kategori_id' => $kategoriId,
                'kelompok_id' => $this->getOrCreateRelationId($row['kelompok'], WarehouseKelompokBarang::class, $this->kelompokCache, true),
                'golongan_id' => $this->getOrCreateRelationId($row['golongan'], WarehouseGolonganBarang::class, $this->golonganCache, true),
                'tipe' => $row['tipe'] ?? null,
                'hna' => $this->parseNumeric($row['harga_beli'] ?? 0),
                'ppn' => $this->parseNumeric($row['ppn_beli_'] ?? $row['ppn_beli'] ?? 0),
                'principal' => $this->getOrCreateRelationId($row['principal'], WarehousePabrik::class, $this->pabrikCache, true),
                'harga_principal' => $this->parseNumeric($row['harga_principal'] ?? 0),
                'diskon_principal' => $this->parseNumeric($row['diskon_principal_'] ?? 0),
                'jenis_obat' => $row['jenis_obat'] ?? null,
                'formularium' => $row['formularium_rs'] ?? null,
                'ppn_rajal' => $this->parseNumeric($row['ppn_jual_rawat_jalan_'] ?? 0),
                'ppn_ranap' => $this->parseNumeric($row['ppn_jual_rawat_inap_'] ?? 0),
                'aktif' => 1,
            ];

            // 3. Pisahkan mana yang akan di-insert dan mana yang akan di-update
            if ($this->existingBarang->has($trimmedNamaBarang)) {
                $barangId = $this->existingBarang[$trimmedNamaBarang];
                $barangToUpdate[$barangId] = $barangData;
            } else {
                $barangToInsert[] = $barangData;
            }

            // Kumpulkan data zat aktif untuk di-sync nanti
            if (!empty($row['zat_aktif'])) {
                $zatAktifIds = [];
                $kodeZatAktifList = array_filter(array_map('trim', explode(',', $row['zat_aktif'])));
                foreach ($kodeZatAktifList as $kodeZatAktif) {
                    if ($this->zatAktifCache->has($kodeZatAktif)) {
                        $zatAktifIds[] = $this->zatAktifCache[$kodeZatAktif];
                    }
                }
                if (!empty($zatAktifIds)) {
                    // Kita butuh ID barang, jadi kita simpan berdasarkan nama barang unik
                    $zatAktifToSync[$trimmedNamaBarang] = $zatAktifIds;
                }
            }
        }

        DB::transaction(function () use ($barangToInsert, $barangToUpdate, $zatAktifToSync) {
            // 4. Eksekusi semua INSERT dalam satu query besar
            if (!empty($barangToInsert)) {
                foreach (array_chunk($barangToInsert, 200) as $chunk) {
                    WarehouseBarangFarmasi::insert($chunk);
                }
            }

            // 5. Eksekusi semua UPDATE
            if (!empty($barangToUpdate)) {
                foreach ($barangToUpdate as $id => $data) {
                    WarehouseBarangFarmasi::where('id', $id)->update($data);
                }
            }

            // 6. Sinkronisasi zat aktif setelah semua barang di-insert/update
            if (!empty($zatAktifToSync)) {
                // Ambil kembali semua ID barang yang baru saja kita proses
                $processedBarang = WarehouseBarangFarmasi::whereIn('nama', array_keys($zatAktifToSync))->pluck('id', 'nama');
                foreach ($zatAktifToSync as $namaBarang => $zatAktifIds) {
                    if ($processedBarang->has($namaBarang)) {
                        $barangId = $processedBarang[$namaBarang];
                        DB::table('warehouse_zat_aktif_barang_farmasi')->where('barang_id', $barangId)->delete();
                        $pivotData = collect($zatAktifIds)->map(fn($zatId) => ['barang_id' => $barangId, 'zat_id' => $zatId])->all();
                        if (!empty($pivotData)) {
                            DB::table('warehouse_zat_aktif_barang_farmasi')->insert($pivotData);
                        }
                    }
                }
            }
        });
    }

    private function getOrCreateRelationId($name, string $modelClass, Collection &$cache, bool $isNullable = false): ?int
    {
        // Logika ini tetap sama dan sudah optimal
        $trimmedName = trim($name ?? '');
        if (empty($trimmedName)) {
            return $isNullable ? null : null;
        }

        if ($cache->has($trimmedName)) {
            return $cache->get($trimmedName);
        }

        $item = $modelClass::firstOrCreate(
            ['nama' => $trimmedName],
            ['kode' => Str::slug($trimmedName, '_')]
        );

        $cache->put($trimmedName, $item->id);

        return $item->id;
    }

    public function chunkSize(): int
    {
        return 1000; // Karena proses di PHP cepat, kita bisa baca chunk lebih besar
    }

    private function parseNumeric($value): float|int
    {
        return is_numeric($value) ? $value : 0;
    }
}
