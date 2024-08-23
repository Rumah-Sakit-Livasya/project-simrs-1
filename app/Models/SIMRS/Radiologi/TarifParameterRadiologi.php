<?php

namespace App\Models\SIMRS\Radiologi;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\ParameterRadiologi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifParameterRadiologi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_parameter_radiologi';
    protected $fillable = ['parameter_radiologi_id', 'group_penjamin_id', 'kelas_rawat_id', 'share_dr', 'share_rs', 'total'];

    /**
     * Get the kelas rawat that owns the tarif.
     */
    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }

    /**
     * Get the group penjamin that owns the tarif.
     */
    public function group_penjamin()
    {
        return $this->belongsTo(GroupPenjamin::class, 'group_penjamin_id');
    }

    /**
     * Get the parameter radiologi that owns the tarif.
     */
    public function parameter_radiologi()
    {
        return $this->belongsTo(ParameterRadiologi::class, 'parameter_radiologi_id');
    }
}
