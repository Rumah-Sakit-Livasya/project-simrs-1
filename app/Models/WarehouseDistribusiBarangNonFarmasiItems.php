<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseDistribusiBarangNonFarmasiItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_distribusi_barang_non_farmasi_item";
    protected $guarded = ["id"];

    public function db()
    {
        return $this->belongsTo(WarehouseDistribusiBarangNonFarmasi::class, 'db_id');
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangNonFarmasi::class, "barang_id");
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id");
    }
}
