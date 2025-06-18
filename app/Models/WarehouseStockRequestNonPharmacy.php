<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockRequestNonPharmacy extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_stock_request_non_pharmacy";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(WarehouseStockRequestNonPharmacyItems::class, "sr_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function asal()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "asal_gudang_id");
    }

    public function tujuan()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "tujuan_gudang_id");
    }
}
