<?php

namespace App\Models;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\ParameterRadiologi;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderRadiologi extends Model
{
    protected $table = 'order_radiologi';

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

    public function parameter_radiologi()
    {
        return $this->belongsToMany(ParameterRadiologi::class, 'order_parameter_radiologi');
    }

    public function order_parameter_radiologi()
    {
        return $this->hasMany(OrderParameterRadiologi::class, 'order_radiologi_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'dokter_radiologi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
