<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakanMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['grup_tindakan_medis_id', 'kode', 'nama_tindakan', 'nama_billing', 'is_konsul', 'auto_charge', 'is_vaksin', 'mapping_rl_13', 'mapping_rl_34'];


    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }

    public function grup_tindakan_medis()
    {
        return $this->belongsTo(GrupTindakanMedis::class);
    }
}
