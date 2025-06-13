<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseOrderNonPharmacyItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "procurement_purchase_order_non_pharmacy_items";
    protected $guarded = ["id"];

    public function pr_item()
    {
        return $this->belongsTo(ProcurementPurchaseRequestNonPharmacyItems::class, 'pri_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangNonFarmasi::class, 'barang_id');
    }

    public function po()
    {
        return $this->belongsTo(ProcurementPurchaseOrderNonPharmacy::class, 'po_id');
    }

    public function pb()
    {
        return $this->hasMany(WarehousePenerimaanBarangNonFarmasiItems::class, 'poi_id');
    }
}
