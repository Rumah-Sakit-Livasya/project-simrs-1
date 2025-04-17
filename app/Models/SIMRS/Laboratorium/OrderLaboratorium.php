<?php

namespace App\Models\SIMRS\Laboratorium;

use App\Models\OrderParameterLaboratorium;
use App\Models\RegistrationOTC;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLaboratorium extends Model
{
    protected $table = 'order_laboratorium';

    use SoftDeletes;

    protected $guarded = [
        'id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function registration_otc()
    {
        return $this->belongsTo(RegistrationOTC::class, 'otc_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function parameter_laboratorium()
    {
        return $this->belongsToMany(ParameterLaboratorium::class, 'order_parameter_laboratorium');
    }

    public function order_parameter_laboratorium()
    {
        return $this->hasMany(OrderParameterLaboratorium::class, 'order_laboratorium_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'dokter_laboratorium_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
