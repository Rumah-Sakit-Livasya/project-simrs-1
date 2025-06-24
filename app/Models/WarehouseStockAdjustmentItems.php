<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockAdjustmentItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_stock_adjustment_item";
    protected $guarded = ["id"];

    public function adjustment()
    {
        return $this->belongsTo(WarehouseStockAdjustment::class, "sa_id");
    }

    public function stored()
    {
        // check if the column has either si_f_id or si_nf_id
        if ($this->si_f_id) {
            return $this->belongsTo(StoredBarangFarmasi::class, 'si_f_id');
        } else { // si_nf_id
            return $this->belongsTo(StoredBarangNonFarmasi::class, 'si_nf_id');
        }
    }
}
