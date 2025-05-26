<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MakananGizi extends Model
{
    use SoftDeletes;
    protected $table = "makanan_gizi";
    protected $guarded = ["id"];

    public function food_orders(){
        return $this->hasMany(OrderMakananGizi::class,"makanan_id","id");
    }
}
