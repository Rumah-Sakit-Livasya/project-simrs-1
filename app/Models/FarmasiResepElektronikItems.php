<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmasiResepElektronikItems extends Model
{
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id');
    }

    public function stored(){
        return $this->belongsTo(StoredBarangFarmasi::class, "si_id");
    }
}
