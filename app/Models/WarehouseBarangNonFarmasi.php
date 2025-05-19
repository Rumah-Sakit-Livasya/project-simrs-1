<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseBarangNonFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_barang_non_farmasi";
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
        return $this->hasMany(WarehouseSatuanTambahanBarangNonFarmasi::class, "barang_id", "id");
    }

    public function smms()
    {
        return $this->hasMany(WarehouseSetupMinMaxStock::class, "barang_nf_id", "id");
    }
}
