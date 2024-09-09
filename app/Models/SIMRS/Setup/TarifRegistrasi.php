<?php

namespace App\Models\SIMRS\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifRegistrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarif_registrasi', $fillable = ['nama_tarif', 'tipe', 'coa'];
}
