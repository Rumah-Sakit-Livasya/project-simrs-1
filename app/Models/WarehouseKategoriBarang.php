<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseKategoriBarang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_kategori_barang";
    protected $guarded = ["id"];

    public function barang_non_farmasi()
    {
        return $this->hasMany(WarehouseBarangNonFarmasi::class, "kategori_id", "id");
    }

    public function barang_farmasi()
    {
        return $this->hasMany(WarehouseBarangFarmasi::class, "kategori_id", "id");
    }
}
