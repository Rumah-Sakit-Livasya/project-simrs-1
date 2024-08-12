<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifKelasRawat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'tarif_kelas_rawat';

    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class);
    }

    public function group_penjamin()
    {
        return $this->belongsTo(GroupPenjamin::class);
    }
}
