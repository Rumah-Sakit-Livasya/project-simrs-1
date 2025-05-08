<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGizi extends Model
{
    use SoftDeletes;
    
    protected $table = "menu_gizi";
    protected $guarded = ["id"];

    public function makanan_menu(){
        return $this->hasMany(MakananMenuGizi::class, "menu_gizi_id", "id");
    }

    public function category(){
        return $this->belongsTo(KategoriGizi::class,"kategori_id","id");
    }
}
