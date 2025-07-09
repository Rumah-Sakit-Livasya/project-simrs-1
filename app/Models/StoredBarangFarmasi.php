<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Carbon\Carbon;

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

    public function adji()
    {
        return $this->hasMany(WarehouseStockAdjustmentItems::class, 'si_f_id');
    }

    public function soi()
    {
        return $this->hasMany(WarehouseStockOpnameItems::class, 'si_f_id');
    }

    public function calculateMovementSince($since): int
    {
        $since = Carbon::parse($since);

        return $this->audits()
            ->where('created_at', '>', $since)
            ->get()
            ->reduce(function ($carry, $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old['qty'], $new['qty'])) {
                    return $carry + ($new['qty'] - $old['qty']);
                }

                return $carry;
            }, 0);
    }
}
