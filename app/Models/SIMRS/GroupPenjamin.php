<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupPenjamin extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'group_penjamin';

    public function penjamin()
    {
        return $this->hasMany(Penjamin::class, 'group_penjamin_id', 'id');
    }

    public function tarif_kelas_rawat()
    {
        return $this->belongsToMany(TarifKelasRawat::class, 'tarif_kelas_rawat');
    }
}
