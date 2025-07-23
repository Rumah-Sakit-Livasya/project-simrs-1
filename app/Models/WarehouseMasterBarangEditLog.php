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
}
