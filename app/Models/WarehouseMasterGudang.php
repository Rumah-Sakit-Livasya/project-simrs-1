<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseMasterGudang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_master_gudang";
    protected $guarded = ['id'];

    public function smms(){
        return $this->hasMany(WarehouseSetupMinMaxStock::class, 'gudang_id','id');
    }

    public function pr_pharmacy(){
        return $this->hasMany(ProcurementPurchaseRequestPharmacy::class, 'gudang_id','id');
    }

    public function pb_pharmacy(){
        return $this->hasMany(WarehousePenerimaanBarangFarmasi::class, 'gudang_id','id');
    }

    public function stored_pharmacy(){
        return $this->hasMany(StoredBarangFarmasi::class, 'gudang_id','id');
    }
}
