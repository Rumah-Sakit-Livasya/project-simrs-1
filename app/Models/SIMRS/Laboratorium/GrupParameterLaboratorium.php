<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupParameterLaboratorium extends Model
{
    use HasFactory;
    protected $table = 'grup_parameter_laboratorium';
    protected $fillable = ['no_urut', 'grup', 'kode_order'];
}
