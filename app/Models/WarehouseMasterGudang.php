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

    public function smms()
    {
        return $this->hasMany(WarehouseSetupMinMaxStock::class, 'gudang_id', 'id');
    }

    public function pr_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestPharmacy::class, 'gudang_id', 'id');
    }

    public function pr_non_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestNonPharmacy::class, 'gudang_id', 'id');
    }

    public function pb_pharmacy()
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasi::class, 'gudang_id', 'id');
    }

    public function stored_pharmacy()
    {
        return $this->hasMany(StoredBarangFarmasi::class, 'gudang_id', 'id');
    }

    public function pb_non_pharmacy()
    {
        return $this->hasMany(WarehousePenerimaanBarangNonFarmasi::class, 'gudang_id', 'id');
    }

    public function stored_non_pharmacy()
    {
        return $this->hasMany(StoredBarangNonFarmasi::class, 'gudang_id', 'id');
    }

    public function asal_sr_pharmacy()
    {
        return $this->hasMany(WarehouseStockRequestPharmacy::class, 'asal_gudang_id', 'id');
    }

    public function tujuan_sr_pharmacy()
    {
        return $this->hasMany(WarehouseStockRequestPharmacy::class, 'tujuan_gudang_id', 'id');
    }

    public function asal_sr_non_pharmacy()
    {
        return $this->hasMany(WarehouseStockRequestNonPharmacy::class, 'asal_gudang_id', 'id');
    }

    public function tujuan_sr_non_pharmacy()
    {
        return $this->hasMany(WarehouseStockRequestNonPharmacy::class, 'tujuan_gudang_id', 'id');
    }

    public function asal_db_pharmacy()
    {
        return $this->hasMany(WarehouseDistribusiBarangFarmasi::class, 'asal_gudang_id', 'id');
    }

    public function tujuan_db_pharmacy()
    {
        return $this->hasMany(WarehouseDistribusiBarangFarmasi::class, 'tujuan_gudang_id', 'id');
    }

    public function stock_adjustment()
    {
        return $this->hasMany(WarehouseStockAdjustment::class, 'gudang_id', 'id');
    }

    public function stock_opname()
    {
        return $this->hasMany(WarehouseStockOpnameGudang::class, 'gudang_id', 'id');
    }

    public function ongoing_stock_opname()
    {
        return $this->hasOne(WarehouseStockOpnameGudang::class, 'gudang_id')
            ->whereNotNull('start')
            ->whereNull('finish');
    }
}
