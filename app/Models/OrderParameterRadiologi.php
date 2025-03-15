<?php

namespace App\Models;

use App\Models\SIMRS\ParameterRadiologi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderParameterRadiologi extends Model
{
    protected $table = 'order_parameter_radiologi';

    use SoftDeletes;

    protected $guarded = ['id'];

    public function order_radiologi()
    {
        return $this->belongsToMany(ParameterRadiologi::class);
    }

    public function parameter_radiologi()
    {
        return $this->belongsTo(ParameterRadiologi::class);
    }
}
