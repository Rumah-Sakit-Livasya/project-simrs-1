<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParameterLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parameter_laboratorium';
    protected $fillable = ['grup_parameter_laboratorium_id', 'kategori_laboratorium_id', 'tipe_laboratorium_id', 'kode', 'parameter', 'satuan', 'status', 'is_hasil', 'is_order', 'tipe_hasil', 'metode', 'no_urut', 'sub_parameter'];
}
