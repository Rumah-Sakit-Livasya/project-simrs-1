<?php

namespace App\Models;

use App\Models\keuangan\ApSupplierDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehousePenerimaanBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_penerimaan_barang_farmasi";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasiItems::class, 'pb_id', 'id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id', 'id');
    }

    public function po()
    {
        return $this->belongsTo(ProcurementPurchaseOrderPharmacy::class, 'po_id', 'id');
    }

    public function apSupplierDetails()
    {
        return $this->morphMany(ApSupplierDetail::class, 'penerimaanBarang');
    }
}
