<?php

namespace App\Models;

use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiReturResep extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(FarmasiReturResepItems::class, 'retur_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
