<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupParameterRadiologi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grup_parameter_radiologi';
    protected $fillable = ['no_urut', 'nama_grup'];
}
