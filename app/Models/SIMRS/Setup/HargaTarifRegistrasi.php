<?php

namespace App\Models\SIMRS\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaTarifRegistrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harga_tarif_registrasi', $fillable = ['group_penjamin_id', 'tarif_registrasi_id', 'harga'];

    public function tarif_registrasi()
    {
        return $this->belongsTo(TarifRegistrasi::class, 'tarif_registrasi_id');
    }
}
