<?php

namespace App\Models;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifTindakanMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_tindakan_medis';
    protected $fillable = ['tindakan_medis_id', 'group_penjamin_id', 'kelas_rawat_id', 'share_dr', 'share_rs', 'prasarana', 'bhp', 'total'];

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
    public function tindakan_medis()
    {
        return $this->belongsTo(TindakanMedis::class, 'tindakan_medis_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function getTarif($groupPenjaminId, $kelasRawatId)
    {
        return $this->tarif()
            ->where('group_penjamin_id', $groupPenjaminId)
            ->where('kelas_rawat_id', $kelasRawatId)
            ->first();
    }
}
