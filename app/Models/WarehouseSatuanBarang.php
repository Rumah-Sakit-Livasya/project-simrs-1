<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseSatuanBarang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_satuan_barang";
    protected $guarded = ["id"];

    public function barang_non_farmasi()
    {
        return $this->hasMany(WarehouseBarangNonFarmasi::class, "satuan_id", "id");
    }

    public function barang_non_farmasi_tambahan()
    {
        return $this->hasMany(WarehouseSatuanTambahanBarangNonFarmasi::class, "satuan_id", "id");
    }
}
