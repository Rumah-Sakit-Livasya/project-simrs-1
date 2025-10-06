<?php

namespace App\Imports;

use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseSatuanTambahanBarangNonFarmasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BarangNonFarmasiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private Collection $satuanCache;
    private Collection $kategoriCache;
    private Collection $kelompokCache;
    private Collection $golonganCache;
    private Collection $existingBarang;

    public function __construct()
    {
        $this->satuanCache = WarehouseSatuanBarang::pluck('id', 'nama');
        $this->kategoriCache = WarehouseKategoriBarang::pluck('id', 'nama');
        $this->kelompokCache = WarehouseKelompokBarang::pluck('id', 'nama');
        $this->golonganCache = WarehouseGolonganBarang::pluck('id', 'nama');
    }

    public function collection(Collection $rows): void
    {
        $namaBarangFromExcel = $rows->pluck('nama_barang')->filter()->unique();
        $this->existingBarang = WarehouseBarangNonFarmasi::whereIn('nama', $namaBarangFromExcel)->pluck('id', 'nama');

        $barangToInsert = [];
        $barangToUpdate = [];
        $satuanTambahanToSync = [];

        foreach ($rows as $row) {
            if (empty($row['nama_barang'])) {
                continue;
            }

            $trimmedNamaBarang = trim($row['nama_barang']);

            $satuanId = $this->getOrCreateRelationId($row['satuan'], WarehouseSatuanBarang::class, $this->satuanCache);
            $kategoriId = $this->getOrCreateRelationId($row['kategori'], WarehouseKategoriBarang::class, $this->kategoriCache);

            if (is_null($kategoriId) || is_null($satuanId)) {
                continue;
            }

            $barangData = [
                'nama' => $trimmedNamaBarang,
                'kode' => $row['kode'] ?? Str::slug($trimmedNamaBarang),
                'satuan_id' => $satuanId,
                'kategori_id' => $kategoriId,
                'kelompok_id' => $this->getOrCreateRelationId($row['kelompok'], WarehouseKelompokBarang::class, $this->kelompokCache, true),
                'golongan_id' => $this->getOrCreateRelationId($row['golongan'], WarehouseGolonganBarang::class, $this->golonganCache, true),
                'hna' => $this->parseNumeric($row['harga_beli'] ?? $row['hna'] ?? 0),
                'ppn' => $this->parseNumeric($row['ppn'] ?? 0),
                'aktif' => isset($row['status_aktif']) ? $this->parseBoolean($row['status_aktif']) : 1,
                'jual_pasien' => isset($row['jual_pasien']) ? $this->parseBoolean($row['jual_pasien']) : 1,
                'keterangan' => $row['keterangan'] ?? null,
            ];

            if ($this->existingBarang->has($trimmedNamaBarang)) {
                $barangId = $this->existingBarang[$trimmedNamaBarang];
                $barangToUpdate[$barangId] = $barangData;
            } else {
                $barangToInsert[] = $barangData;
            }

            // Satuan tambahan: format kolom: satuan_tambahan (nama1|jumlah1|aktif1, nama2|jumlah2|aktif2, ...)
            if (!empty($row['satuan_tambahan'])) {
                $satuanTambahanList = array_filter(array_map('trim', explode(',', $row['satuan_tambahan'])));
                foreach ($satuanTambahanList as $satuanTambahanStr) {
                    $parts = array_map('trim', explode('|', $satuanTambahanStr));
                    $namaSatuan = $parts[0] ?? null;
                    $jumlah = isset($parts[1]) ? (int)$parts[1] : 0;
                    $aktif = isset($parts[2]) ? $this->parseBoolean($parts[2]) : 1;
                    if ($namaSatuan) {
                        $satuanIdTambahan = $this->getOrCreateRelationId($namaSatuan, WarehouseSatuanBarang::class, $this->satuanCache);
                        $satuanTambahanToSync[$trimmedNamaBarang][] = [
                            'satuan_id' => $satuanIdTambahan,
                            'isi' => $jumlah,
                            'aktif' => $aktif,
                        ];
                    }
                }
            }
        }

        DB::transaction(function () use ($barangToInsert, $barangToUpdate, $satuanTambahanToSync) {
            if (!empty($barangToInsert)) {
                foreach (array_chunk($barangToInsert, 200) as $chunk) {
                    WarehouseBarangNonFarmasi::insert($chunk);
                }
            }

            if (!empty($barangToUpdate)) {
                foreach ($barangToUpdate as $id => $data) {
                    WarehouseBarangNonFarmasi::where('id', $id)->update($data);
                }
            }

            if (!empty($satuanTambahanToSync)) {
                $processedBarang = WarehouseBarangNonFarmasi::whereIn('nama', array_keys($satuanTambahanToSync))->pluck('id', 'nama');
                foreach ($satuanTambahanToSync as $namaBarang => $satuanTambahanArr) {
                    if ($processedBarang->has($namaBarang)) {
                        $barangId = $processedBarang[$namaBarang];
                        WarehouseSatuanTambahanBarangNonFarmasi::where('barang_id', $barangId)->forceDelete();
                        foreach ($satuanTambahanArr as $satuanData) {
                            WarehouseSatuanTambahanBarangNonFarmasi::create([
                                'barang_id' => $barangId,
                                'satuan_id' => $satuanData['satuan_id'],
                                'isi' => $satuanData['isi'],
                                'aktif' => $satuanData['aktif'],
                            ]);
                        }
                    }
                }
            }
        });
    }

    private function getOrCreateRelationId($name, string $modelClass, Collection &$cache, bool $isNullable = false): ?int
    {
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
        return 1000;
    }

    private function parseNumeric($value): float|int
    {
        return is_numeric($value) ? $value : 0;
    }

    private function parseBoolean($value): bool|int
    {
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }
        $val = strtolower(trim((string)$value));
        return in_array($val, ['1', 'ya', 'y', 'true', 'aktif']) ? 1 : 0;
    }
}
