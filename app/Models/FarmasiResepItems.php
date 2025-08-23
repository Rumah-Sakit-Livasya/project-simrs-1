<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepItems extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function stored()
    {
        return $this->belongsTo(StoredBarangFarmasi::class, "si_id");
    }

    public function racikan()
    {
        return $this->belongsTo(FarmasiResepItems::class, "racikan_id");
    }

    public function detail_racikan()
    {
        return $this->hasMany(FarmasiResepItems::class, "racikan_id");
    }

    public function resep(){
        return $this->belongsTo(FarmasiResep::class, 'resep_id');
    }

    public function getHargaRacikan(){
        if ($this->tipe != 'racikan') return 0;

        $harga = 0;
        foreach($this->detail_racikan as $item){
            $harga += $item->subtotal;
        }
        return $harga;
    }
}
