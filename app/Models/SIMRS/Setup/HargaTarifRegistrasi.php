<?php

namespace App\Models\SIMRS\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaTarifRegistrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harga_tarif_regitrasi', $fillable = ['group_penjamin_id', 'tarif_registrasi_id', 'harga'];
}
