<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiParameterLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nilai_parameter_laboratorium';
}
