<?php

namespace App\Models\SIMRS\Operasi;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use Illuminate\Database\Eloquent\Model;

class TarifOperasi extends Model
{
    protected $table = 'tarif_operasi';
    protected $guarded = ['id'];

    /**
     * Get the group penjamin that owns the tarif.
     */
    public function group_penjamin()
    {
        return $this->belongsTo(GroupPenjamin::class, 'group_penjamin_id');
    }

    /**
     * Get the group penjamin that owns the tarif.
     */
    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }

    /**
     * Get the persalinan that owns the tarif.
     */
    public function tindakanOperasi()
    {
        return $this->belongsTo(TindakanOperasi::class, 'tindakan_operasi_id');
    }
}
