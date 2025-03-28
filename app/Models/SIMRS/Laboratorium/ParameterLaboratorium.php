<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParameterLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parameter_laboratorium';
    protected $fillable = ['grup_parameter_laboratorium_id', 'kategori_laboratorium_id', 'tipe_laboratorium_id', 'kode', 'parameter', 'satuan', 'status', 'is_hasil', 'is_order', 'tipe_hasil', 'metode', 'no_urut', 'sub_parameter'];

    /**
     * Get the sub-parameters for this main parameter.
     */
    public function subParameters(): BelongsToMany
    {
        return $this->belongsToMany(ParameterLaboratorium::class, 'relasi_parameter_laboratorium', 'main_parameter_id', 'sub_parameter_id');
    }

    /**
     * Get the main parameters for this sub-parameter.
     */
    public function mainParameters(): BelongsToMany
    {
        return $this->belongsToMany(ParameterLaboratorium::class, 'relasi_parameter_laboratorium', 'sub_parameter_id', 'main_parameter_id');
    }
}
