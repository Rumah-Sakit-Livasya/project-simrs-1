<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MakananMenuGizi extends Model
{

    use SoftDeletes;

    protected $table = "makanan_menu_gizi";
    protected $guarded = ["id"];

    public function makanan(){
        return $this->hasOne(MakananGizi::class, "id", "makanan_id");
    }

    public function menu(){
        return $this->belongsTo(MenuGizi::class,"id","menu_gizi_id");
    }
}
