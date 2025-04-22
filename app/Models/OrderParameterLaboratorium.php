<?php

namespace App\Models;

use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;


class OrderParameterLaboratorium extends Model
{
    protected $table = 'order_parameter_laboratorium';

    use SoftDeletes;

    protected $guarded = ['id'];

    public function order_laboratorium()
    {
        return $this->belongsTo(OrderLaboratorium::class, 'order_laboratorium_id');
    }

    public function parameter_laboratorium()
    {
        return $this->belongsTo(ParameterLaboratorium::class, 'parameter_laboratorium_id');
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
        return $this->hasOneThrough(Registration::class, OrderLaboratorium::class, 'id', 'id', 'order_laboratorium_id', 'registration_id');
    }

    public function registration_otc()
    {
        return $this->hasOneThrough(RegistrationOTC::class, OrderLaboratorium::class, 'id', 'id', 'order_laboratorium_id', 'otc_id');
    }
}
