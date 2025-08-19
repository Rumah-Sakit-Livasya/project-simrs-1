<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseReturBarang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_retur_barang";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(WarehouseReturBarangItems::class, "rb_id");
    }

    public function supplier()
    {
        return $this->belongsTo(WarehouseSupplier::class, "supplier_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
