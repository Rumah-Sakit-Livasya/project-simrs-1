<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_laboratorium';
    protected $fillable = ['nama_kategori', 'status'];

    public function parameter_laboratorium()
    {
        return $this->hasMany(ParameterLaboratorium::class);
    }
}
