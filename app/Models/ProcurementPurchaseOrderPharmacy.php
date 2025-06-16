<?php

namespace App\Models;

use App\Models\keuangan\PenerimaanBarangHeader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function penerimaanBarang(): MorphMany
    {
        return $this->morphMany(PenerimaanBarangHeader::class, 'purchasable');
    }
}
