<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockRequestPharmacyItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_stock_request_pharmacy_item";
    protected $guarded = ["id"];

    public function sr()
    {
        return $this->belongsTo(WarehouseStockRequestPharmacy::class, "sr_id");
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, "barang_id");
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id");
    }
}
