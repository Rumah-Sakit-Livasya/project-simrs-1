<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseZatAktifBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_zat_aktif_barang_farmasi";
    protected $guarded = ["id"];

    public function zat()
    {
        return $this->belongsTo(WarehouseZatAktif::class, "zat_id", "id");
    }

    public function barang(){
        return $this->belongsTo(WarehouseBarangFarmasi::class,"barang_id", "id");
    }
}
