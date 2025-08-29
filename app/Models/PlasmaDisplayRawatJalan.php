<?php

namespace App\Models;

use App\Models\SIMRS\Departement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlasmaDisplayRawatJalan extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_active'];

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'departement_plasma_display');
    }
}
