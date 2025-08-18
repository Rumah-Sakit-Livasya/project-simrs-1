<?php

namespace App\Models;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

class FarmasiResep extends Model
{
    protected $guarded = ["id"];

    public function registration()
    {
        return $this->belongsTo(Registration::class, "registration_id");
    }

    public function otc()
    {
        return $this->belongsTo(RegistrationOTC::class, "otc_id");
    }

    public function re()
    {
        return $this->belongsTo(FarmasiResepElektronik::class, "re_id");
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, "dokter_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, "gudang_id");
    }

    public function items()
    {
        return $this->hasMany(FarmasiResepItems::class, "resep_id");
    }
}
