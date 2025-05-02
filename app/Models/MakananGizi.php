<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakananGizi extends Model
{
    protected $table = "makanan_gizi";
    protected $guarded = ["id"];

    public function food_orders(){
        return $this->hasMany(OrderMakananGizi::class,"makanan_id","id");
    }
}
