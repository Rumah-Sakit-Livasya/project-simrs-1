<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseMasterBarangEditLog extends Model
{
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->morphTo(__FUNCTION__, 'goods_type', 'goods_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function satuan()
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, "satuan_id", "id");
    }

    public function golongan()
    {
        return $this->belongsTo(WarehouseGolonganBarang::class, "golongan_id", "id");
    }

    public function kelompok()
    {
        return $this->belongsTo(WarehouseKelompokBarang::class, "kelompok_id", "id");
    }
}
