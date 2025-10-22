<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Carbon\Carbon;

class WarehouseStockRequestPharmacy extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_stock_request_pharmacy";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(WarehouseStockRequestPharmacyItems::class, "sr_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function asal()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "asal_gudang_id");
    }

    public function tujuan()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "tujuan_gudang_id");
    }

    public function db(){
        return $this->hasMany(WarehouseDistribusiBarangFarmasi::class, "sr_id");
    }

    /**
     * Check if the stock request can be edited
     * Can edit if status is draft OR if status is final but not older than 1 week
     *
     * @return bool
     */
    public function canEdit()
    {
        if ($this->status == 'draft') {
            return true;
        }

        if ($this->status == 'final') {
            $oneWeekAgo = Carbon::now()->subWeek();
            return Carbon::parse($this->tanggal_sr)->isAfter($oneWeekAgo);
        }

        return false;
    }
}
