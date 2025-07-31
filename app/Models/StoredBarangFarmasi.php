<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class StoredBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "stored_barang_farmasi";
    protected $guarded = ["id"];

    public function pbi()
    {
        return $this->belongsTo(WarehousePenerimaanBarangFarmasiItems::class, 'pbi_id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function rbi()
    {
        return $this->hasMany(WarehouseReturBarangItems::class, 'si_f_id');
    }

    public function barang()
    {
        return $this->hasOneThrough(
            \App\Models\WarehouseBarangFarmasi::class,
            \App\Models\WarehousePenerimaanBarangFarmasiItems::class,
            'id',
            'id',
            'pbi_id',
            'barang_id'
        );
    }
}
