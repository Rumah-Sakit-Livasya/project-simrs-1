<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiParameterLaboratorium extends Model
{
    protected $table = "relasi_parameter_laboratorium";
    protected $fillable = ['main_parameter_id', 'sub_parameter_id'];
}
