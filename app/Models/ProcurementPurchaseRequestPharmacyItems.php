<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseRequestPharmacyItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "procurement_purchase_request_pharmacy_items";
    protected $guarded = ["id"];

    public function pr()
    {
        return $this->belongsTo(ProcurementPurchaseRequestPharmacy::class, 'pr_id');
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id');
    }

    public function po_items(){
        return $this->hasMany(ProcurementPurchaseOrderPharmacyItems::class, 'pri_id');
    }
}
