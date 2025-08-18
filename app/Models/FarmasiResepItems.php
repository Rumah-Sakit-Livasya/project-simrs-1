<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmasiResepItems extends Model
{
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
}
