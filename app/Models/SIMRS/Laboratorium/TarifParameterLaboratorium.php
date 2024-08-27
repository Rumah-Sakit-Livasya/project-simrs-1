<?php

namespace App\Models\SIMRS\Laboratorium;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\ParameterRadiologi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifParameterLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_parameter_laboratorium';
    protected $fillable = ['parameter_laboratorium_id', 'group_penjamin_id', 'kelas_rawat_id', 'share_dr', 'share_rs', 'prasarana', 'bhp', 'total'];

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
    public function parameter_laboratorium()
    {
        return $this->belongsTo(ParameterLaboratorium::class, 'parameter_laboratorium_id');
    }
}
