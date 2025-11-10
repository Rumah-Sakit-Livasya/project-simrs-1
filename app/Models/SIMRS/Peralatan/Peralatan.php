<?php

namespace App\Models\SIMRS\Peralatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peralatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peralatan';
    protected $fillable = ['kode', 'nama', 'satuan_pakai', 'is_req_dokter'];
    /**
     * Relasi ke tabel tarif peralatan.
     */
    public function tarif_peralatan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TarifPeralatan::class, 'peralatan_id', 'id');
    }
}
