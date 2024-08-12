<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasRawat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'kelas_rawat';

    public function rooms()
    {
        return $this->hasMany(Room::class, 'kelas_rawat_id', 'id');
    }

    public function tarif_kelas_rawat()
    {
        return $this->hasMany(TarifKelasRawat::class, 'kelas_rawat_id', 'id');
    }
}
