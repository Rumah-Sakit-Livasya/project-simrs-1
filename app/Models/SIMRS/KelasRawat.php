<?php

namespace App\Models\SIMRS;

use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Peralatan\TarifPeralatan;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function beds()
    {
        return $this->hasManyThrough(Bed::class, Room::class);
    }

    public function bedTambahan()
    {
        return $this->hasManyThrough(Bed::class, Room::class)
            ->where('beds.is_tambahan', 1);
    }

    public function bedBor()
    {
        return $this->hasManyThrough(Bed::class, Room::class)
            ->where('beds.is_tambahan', 0);
    }

    public function tarif_kelas_rawat()
    {
        return $this->belongsToMany(TarifKelasRawat::class, 'tarif_kelas_rawat');
    }

    public function tarif_parameter_radiologi()
    {
        return $this->hasMany(TarifParameterRadiologi::class, 'kelas_rawat_id');
    }

    public function tarif_parameter_laboratorium()
    {
        return $this->hasMany(TarifParameterLaboratorium::class, 'kelas_rawat_id');
    }

    public function tarif_peralatan()
    {
        return $this->hasMany(TarifPeralatan::class, 'kelas_rawat_id');
    }
}
