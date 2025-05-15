<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseGolonganBarang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_golongan_barang";
    protected $guarded = ["id"];

    public function barang_non_farmasi()
    {
        return $this->hasMany(WarehouseBarangNonFarmasi::class, "golongan_id", "id");
    }
}
