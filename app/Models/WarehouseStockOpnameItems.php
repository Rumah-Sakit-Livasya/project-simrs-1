<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockOpnameItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_stock_opname_item";
    protected $guarded = ["id"];

    public function opname()
    {
        return $this->belongsTo(WarehouseStockOpnameGudang::class, "sog_id");
    }
}
