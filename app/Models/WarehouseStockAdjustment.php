<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseStockAdjustment extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_stock_adjustment";
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function authorized_user()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }

    public function items()
    {
        return $this->hasMany(WarehouseStockAdjustmentItems::class, "sa_id");
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "gudang_id");
    }

    public function barang()
    {
        // check if the column has either barang_f_id or barang_nf_id
        if ($this->barang_f_id) {
            return $this->belongsTo(WarehouseBarangFarmasi::class, 'barang_f_id');
        } else { // barang_nf_id
            return $this->belongsTo(WarehouseBarangNonFarmasi::class, 'barang_nf_id');
        }
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id");
    }
}
