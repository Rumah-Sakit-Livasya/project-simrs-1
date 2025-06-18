<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_barang_farmasi";
    protected $guarded = ["id"];

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id", "id");
    }

    public function kategori()
    {
        return $this->belongsTo(WarehouseKategoriBarang::class, "kategori_id", "id");
    }

    public function golongan()
    {
        return $this->belongsTo(WarehouseGolonganBarang::class, "golongan_id", "id");
    }

    public function kelompok()
    {
        return $this->belongsTo(WarehouseKelompokBarang::class, "kelompok_id", "id");
    }

    public function satuan_tambahan()
    {
        return $this->hasMany(WarehouseSatuanTambahanBarangFarmasi::class, "barang_id", "id");
    }

    public function zat_aktif()
    {
        return $this->hasMany(WarehouseZatAktifBarangFarmasi::class, "barang_id", "id");
    }

    public function smms()
    {
        return $this->hasMany(WarehouseSetupMinMaxStock::class, "barang_f_id", "id");
    }

    public function pr_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestPharmacyItems::class, "barang_id", "id");
    }

    public function po_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacyItems::class, "barang_id", "id");
    }

    public function pb_pharmacy(){
        return $this->hasMany(WarehousePenerimaanBarangFarmasiItems::class, "barang_id", "id");
    }

    public function sr_pharmacy(){
        return $this->hasMany(WarehouseStockRequestPharmacyItems::class, "barang_id", "id");
    }
}
