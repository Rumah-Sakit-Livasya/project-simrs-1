<?php

namespace App\Models;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepHarian extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function items()
    {
        return $this->hasMany(FarmasiResepHarianItems::class, 'rh_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
