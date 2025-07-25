<?php

namespace App\Models\SIMRS\Pelayanan;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

class RujukAntarRS extends Model
{
    protected $table = 'rujuk_antar_rs';

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
