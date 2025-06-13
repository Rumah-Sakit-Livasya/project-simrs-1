<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseOrderPharmacy extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "procurement_purchase_order_pharmacy";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacyItems::class, 'po_id');
    }

    public function supplier()
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pb(){
        return $this->hasMany(WarehousePenerimaanBarangFarmasi::class, 'po_id');
    }
}
