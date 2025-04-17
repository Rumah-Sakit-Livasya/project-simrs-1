<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupParameterLaboratorium extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'grup_parameter_laboratorium';
    protected $fillable = ['no_urut', 'nama_grup', 'kode_order'];

    public function parameter_laboratorium()
    {
        return $this->hasMany(ParameterLaboratorium::class);
    }
}
