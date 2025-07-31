<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderGizi extends Model
{

    use SoftDeletes;

    protected $table = "order_gizi";
    protected $guarded = ["id"];

    public function registration(){
        return $this->belongsTo(Registration::class,"registration_id");
    }

    public function foods(){
        return $this->hasMany(OrderMakananGizi::class,"order_id" );
    }

    public function category(){
        return $this->belongsTo(KategoriGizi::class,"kategori_id");
    }
}
