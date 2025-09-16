<?php

namespace App\Models\SIMRS;

use App\Models\OrderRadiologi;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParameterRadiologi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parameter_radiologi';
    protected $fillable = ['grup_parameter_radiologi_id', 'kategori_radiologi_id', 'is_reverse', 'is_kontras', 'kode', 'parameter'];

    public function grup_parameter_radiologi()
    {
        return $this->belongsTo(GrupParameterRadiologi::class);
    }

    public function kategori_radiologi()
    {
        return $this->belongsTo(KategoriRadiologi::class);
    }

    public function order_radiologi()
    {
        return $this->belongsToMany(OrderRadiologi::class, 'order_parameter_radiologi');
    }

    public function tarif_parameter_radiologi()
    {
        return $this->hasMany(TarifParameterRadiologi::class, 'parameter_radiologi_id');
    }
}
