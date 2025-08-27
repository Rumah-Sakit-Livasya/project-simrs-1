<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockAdjustmentUsers extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_stock_adjustment_user";
    protected $guarded = ["id"];

    public function stock_adjustment()
    {
        return $this->belongsTo(WarehouseStockAdjustment::class, 'authorized_user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
