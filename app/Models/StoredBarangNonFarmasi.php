<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class StoredBarangNonFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "stored_barang_non_farmasi";
    protected $guarded = ["id"];

    public function pbi()
    {
        return $this->belongsTo(WarehousePenerimaanBarangNonFarmasiItems::class, 'pbi_id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function rbi()
    {
        return $this->hasMany(WarehouseReturBarangItems::class, 'si_nf_id');
    }

    public function adji()
    {
        return $this->hasMany(WarehouseStockAdjustmentItems::class, 'si_nf_id');
    }
}
