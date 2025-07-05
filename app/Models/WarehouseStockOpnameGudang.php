<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockOpnameGudang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_stock_opname_gudang";
    protected $guarded = ["id"];

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "gudang_id");
    }

    public function start_user()
    {
        return $this->belongsTo(User::class, "start_user_id");
    }

    public function finish_user()
    {
        return $this->belongsTo(User::class, "finish_user_id");
    }

    public function items()
    {
        return $this->hasMany(WarehouseStockOpnameItems::class, "sog_id");
    }
}
