<?php

namespace App\Models\SIMRS;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Setup\TarifRegistrasi;
use Illuminate\Database\Eloquent\Model;

class TarifRegistrasiDepartement extends Model
{
    protected $fillable = ['tarif_registrasi_id', 'departement_id'];

    public function tarif_registrasi()
    {
        return $this->belongsTo(TarifRegistrasi::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
}
