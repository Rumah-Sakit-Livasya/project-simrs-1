<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DietGizi extends Model
{

    use SoftDeletes;

    protected $table = "diet_gizi";
    protected $guarded = ["id"];

    public function registration(){
        return $this->hasOne(Registration::class);
    }

    public function category(){
        return $this->belongsTo(KategoriGizi::class, "kategori_id");
    }
}
