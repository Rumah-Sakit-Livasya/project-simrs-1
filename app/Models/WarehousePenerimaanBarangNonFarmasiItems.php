<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehousePenerimaanBarangNonFarmasiItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_penerimaan_barang_non_farmasi_item";
    protected $guarded = ["id"];

    public function pb(){
        return $this->belongsTo(WarehousePenerimaanBarangNonFarmasi::class, 'pb_id');
    }

    public function item(){
        return $this->belongsTo(WarehouseBarangNonFarmasi::class, 'item_id');
    }

    public function satuan(){
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id');
    }

    public function stored_items(){
        return $this->hasMany(StoredBarangNonFarmasi::class, 'pbi_id');
    }

    public function poi(){
        return $this->belongsTo(ProcurementPurchaseOrderNonPharmacyItems::class, 'poi_id');
    }
}
