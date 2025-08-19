<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseSatuanTambahanBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_satuan_tambahan_barang_farmasi";
    protected $guarded = ["id"];

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id", "id");
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class,"barang_id","id");
    }
}
