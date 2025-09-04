<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepHarianItems extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function rh()
    {
        return $this->belongsTo(FarmasiResepHarian::class, 'rh_id');
    }

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, 'barang_id');
    }

    public function getTerakhirDiberiAttribute()
    {
        // fetch the latest $fri from FarmasiResepItems
        // where rhi_id == $this->id
        $fri = FarmasiResepItems::where('rhi_id', $this->id)->orderBy('created_at', 'desc')->first();

        return $fri ? $fri->created_at : null;
    }

    public function getSelesaiAttribute()
    {
        return ($this->qty_perhari * $this->qty_hari) <= $this->qty_diberi;
    }
}
