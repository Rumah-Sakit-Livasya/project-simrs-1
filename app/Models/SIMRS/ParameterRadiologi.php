<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParameterRadiologi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parameter_radiologi';
    protected $fillable = ['grup_parameter_radiologi_id', 'kategori_radiologi_id', 'status', 'parameter'];
}
