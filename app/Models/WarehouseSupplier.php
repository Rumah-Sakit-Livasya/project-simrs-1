<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseSupplier extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_supplier";
    protected $guarded = ["id"];

    public function po_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacy::class, "supplier_id");
    }

    public function po_non_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderNonPharmacy::class, "supplier_id");
    }

    public function pb_pharmacy()
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasi::class, "supplier_id");
    }

    public function pb_non_pharmacy()
    {
        return $this->hasMany(WarehousePenerimaanBarangNonFarmasi::class, "supplier_id");
    }

    public function rb(){
        return $this->hasMany(WarehouseReturBarang::class, "supplier_id");
    }
}
