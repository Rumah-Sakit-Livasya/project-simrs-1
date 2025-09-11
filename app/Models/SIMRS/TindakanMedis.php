<?php

namespace App\Models\SIMRS;

use App\Models\TarifTindakanMedis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakanMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['grup_tindakan_medis_id', 'kode', 'nama_tindakan', 'nama_billing', 'is_konsul', 'auto_charge', 'is_vaksin', 'mapping_rl_13', 'mapping_rl_34'];

    public function tagihan_pasien()
    {
        return $this->hasOne(TagihanPasien::class);
    }

    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }

    public function grup_tindakan_medis()
    {
        return $this->belongsTo(GrupTindakanMedis::class);
    }

    public function tarifTindakanMedis()
    {
        return $this->hasMany(TarifTindakanMedis::class, 'tindakan_medis_id');
    }

    // public function getTotalTarif($groupPenjaminId, $kelasRawatId)
    // {
    //     // Cari tarif yang sesuai dengan filter
    //     $tarif = $this->tarifTindakanMedis()
    //         ->where('group_penjamin_id', $groupPenjaminId)
    //         ->where('kelas_rawat_id', $kelasRawatId)
    //         ->first();

    //     return $tarif ? $tarif->total : 0;
    // }

    public function getTotalTarif($groupPenjaminId, $kelasRawatId)
    {
        $tarif = $this->getTarif($groupPenjaminId, $kelasRawatId);
        return $tarif ? $tarif->total : 0;
    }
    public function getTarif($groupPenjaminId, $kelasRawatId)
    {
        return $this->tarifTindakanMedis()
            ->where('group_penjamin_id', $groupPenjaminId)
            ->where('kelas_rawat_id', $kelasRawatId)
            ->first();
    }

    // Method untuk mendapatkan share_dr
    public function getShareDr($groupPenjaminId, $kelasRawatId)
    {
        $tarif = $this->getTarif($groupPenjaminId, $kelasRawatId);
        return $tarif ? $tarif->share_dr : 0;
    }
}
