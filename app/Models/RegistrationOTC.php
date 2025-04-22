<?php

namespace App\Models;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Penjamin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationOTC extends Model
{
    protected $table = 'registration_otc';
    protected $guarded = ['id'];

    use SoftDeletes;

    public function order_radiologi()
    {
        return $this->hasOne(OrderRadiologi::class, 'otc_id');
    }

    public function order_laboratorium()
    {
        return $this->hasOne(OrderLaboratorium::class, 'otc_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id");
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, "penjamin_id");
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, "departement_id");
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, "doctor_id");
    }
}
