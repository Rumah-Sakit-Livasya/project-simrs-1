<?php

namespace App\Models\SIMRS\Persalinan;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Persalinan\DaftarPersalinan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifPersalinan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_persalinan';
    protected $guarded = ['id'];

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
    public function persalinan()
    {
        return $this->belongsTo(DaftarPersalinan::class, 'persalinan_id');
    }
}
