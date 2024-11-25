<?php

namespace App\Models\SIMRS;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupTindakanMedis extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'grup_tindakan_medis';
    protected $fillable = ['departement_id', 'nama_grup', 'status', 'coa_pendapatan', 'coa_prasarana', 'coa_bhp', 'coa_biaya'];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function tindakan_medis()
    {
        return $this->hasMany(TindakanMedis::class);
    }
}
