<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepHarianItems extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function barang(){
        return $this->belongsTo(WarehouseBarangFarmasi::class, "barang_id");
    }
}
