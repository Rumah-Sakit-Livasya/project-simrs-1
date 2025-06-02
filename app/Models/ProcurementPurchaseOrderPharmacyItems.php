<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseOrderPharmacyItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "procurement_purchase_order_pharmacy_items";
    protected $guarded = ["id"];

    public function pr_item()
    {
        return $this->belongsTo(ProcurementPurchaseRequestPharmacyItems::class, 'pri_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, 'barang_id');
    }

    public function po()
    {
        return $this->belongsTo(ProcurementPurchaseOrderPharmacy::class, 'po_id');
    }
}
