<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $guarded = ['id'];
    protected $table = 'stock_transactions';

    public function stock()
    {
        return $this->morphTo(__FUNCTION__, 'stock_model', 'stock_id');
    }

    public function source()
    {
        return $this->morphTo(__FUNCTION__, 'source_model', 'source_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function before_gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "before_gudang_id");
    }

    public function after_gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "after_gudang_id");
    }
}
