<?php

namespace App\Models;

use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

class FarmasiResepElektronik extends Model
{

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function items()
    {
        return $this->hasMany(FarmasiResepElektronikItems::class, "re_id");
    }

    public function cppt(){
        return $this->belongsTo(CPPT::class, 'cppt_id');
    }
}
