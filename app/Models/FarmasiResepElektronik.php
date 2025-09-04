<?php

namespace App\Models;

use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepElektronik extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function resep()
    {
        return $this->hasOne(FarmasiResep::class, 're_id');
    }

    public function items()
    {
        return $this->hasMany(FarmasiResepElektronikItems::class, 're_id');
    }

    public function cppt()
    {
        return $this->belongsTo(CPPT::class, 'cppt_id');
    }
}
