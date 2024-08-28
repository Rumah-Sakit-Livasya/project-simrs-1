<?php

namespace App\Models\SIMRS\Peralatan;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifPeralatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_peralatan';
    protected $fillable = ['peralatan_id', 'group_penjamin_id', 'kelas_rawat_id', 'share_dr', 'share_rs', 'total'];

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
}
