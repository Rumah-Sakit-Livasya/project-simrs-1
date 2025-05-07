<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

class DietGizi extends Model
{
    protected $table = "diet_gizi";
    protected $guarded = ["id"];

    public function registration(){
        return $this->hasOne(Registration::class);
    }

    public function category(){
        return $this->belongsTo(KategoriGizi::class, "kategori_id");
    }
}
