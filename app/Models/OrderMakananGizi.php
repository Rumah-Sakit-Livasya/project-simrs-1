<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMakananGizi extends Model
{
    protected $table = "order_makanan_gizi";
    protected $guarded = ["id"];

    public function order(){
        return $this->belongsTo(OrderGizi::class);
    }

    public function food(){
        return $this->belongsTo(MakananGizi::class, "makanan_id", "id");
    }
}
