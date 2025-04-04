<?php

namespace App\Models;

use App\Models\SIMRS\ParameterRadiologi;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderParameterRadiologi extends Model
{
    protected $table = 'order_parameter_radiologi';

    use SoftDeletes;

    protected $guarded = ['id'];

    public function order_radiologi()
    {
        return $this->belongsTo(OrderRadiologi::class, 'order_radiologi_id');
    }

    public function parameter_radiologi()
    {
        return $this->belongsTo(ParameterRadiologi::class);
    }

    public function radiografer()
    {
        return $this->belongsTo(Employee::class, 'radiografer_id', 'id');
    }

    public function verifikator()
    {
        return $this->belongsTo(Employee::class, 'verifikator_id', 'id');
    }

    public function registration()
    {
        return $this->hasOneThrough(Registration::class, OrderRadiologi::class, 'id', 'id', 'order_radiologi_id', 'registration_id');
    }

    public function registration_otc()
    {
        return $this->hasOneThrough(RegistrationOTC::class, OrderRadiologi::class, 'id', 'id', 'order_radiologi_id', 'otc_id');
    }
}
