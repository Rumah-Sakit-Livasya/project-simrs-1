<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMakananGizi extends Model
{
    use SoftDeletes;

    protected $table = "order_makanan_gizi";
    protected $guarded = ["id"];

    public function order(){
        return $this->belongsTo(OrderGizi::class);
    }

    public function food(){
        return $this->belongsTo(MakananGizi::class, "makanan_id", "id");
    }
}
