<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = 'warehouse_barang_farmasi';

    protected $guarded = ['id'];

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(WarehouseKategoriBarang::class, 'kategori_id', 'id');
    }

    public function golongan()
    {
        return $this->belongsTo(WarehouseGolonganBarang::class, 'golongan_id', 'id');
    }

    public function kelompok()
    {
        return $this->belongsTo(WarehouseKelompokBarang::class, 'kelompok_id', 'id');
    }

    public function satuan_tambahan()
    {
        return $this->hasMany(WarehouseSatuanTambahanBarangFarmasi::class, 'barang_id', 'id');
    }

    public function zat_aktif()
    {
        return $this->belongsToMany(
            WarehouseZatAktif::class,           // 1. Model tujuan
            'warehouse_zat_aktif_barang_farmasi', // 2. Nama tabel pivot
            'barang_id',                        // 3. Foreign key di pivot untuk model ini
            'zat_id'                            // 4. Foreign key di pivot untuk model tujuan
        );
    }

    public function smms()
    {
        return $this->hasMany(WarehouseSetupMinMaxStock::class, 'barang_f_id', 'id');
    }

    public function pr_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestPharmacyItems::class, 'barang_id', 'id');
    }

    public function po_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacyItems::class, 'barang_id', 'id');
    }

    public function pb_pharmacy()
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasiItems::class, 'barang_id', 'id');
    }

    public function sr_pharmacy()
    {
        return $this->hasMany(WarehouseStockRequestPharmacyItems::class, 'barang_id', 'id');
    }

    public function db_pharmacy()
    {
        return $this->hasMany(WarehouseDistribusiBarangFarmasiItems::class, 'barang_id', 'id');
    }

    public function stock_adjustment()
    {
        return $this->hasMany(WarehouseStockAdjustment::class, 'barang_f_id', 'id');
    }

    public function stored_items()
    {
        return $this->hasManyThrough(StoredBarangFarmasi::class, WarehousePenerimaanBarangFarmasiItems::class, 'barang_id', 'pbi_id');
    }

    public function pabrik()
    {
        return $this->belongsTo(WarehousePabrik::class, 'principal');
    }

    public function getGudangsAttribute()
    {
        return $this->pb_pharmacy
            ->flatMap(function ($pbi) {
                return $pbi->stored_items->map(function ($item) {
                    return $item->gudang;
                });
            })
            ->unique('id') // Optional: avoid duplicates
            ->values();
    }

    public function stokGudang()
    {
        return $this->hasManyThrough(
            StoredBarangFarmasi::class,                 // 1. Model tujuan akhir (tabel stok)
            WarehousePenerimaanBarangFarmasiItems::class, // 2. Model perantara (tabel item penerimaan)
            'barang_id',                                // 3. Foreign key di tabel perantara (pbi) yang merujuk ke model ini (barang)
            'pbi_id',                                   // 4. Foreign key di tabel tujuan (stok) yang merujuk ke tabel perantara (pbi)
            'id',                                       // 5. Primary key di model ini (barang)
            'id'                                        // 6. Primary key di model perantara (pbi)
        );
    }
}
