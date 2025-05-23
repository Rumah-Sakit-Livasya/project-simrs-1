<?php

namespace App\Models\keuangan;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

// app/Models/JasaDokter.php
class JasaDokter extends Model
{
    protected $table = 'jasa_dokter';

    protected $fillable = [
        'registration_id',
        'dokter_id',
        'order_tindakan_medis_id',
        'nama_tindakan',
        'nominal',
        'diskon',
        'ppn_persen',
        'jkp',
        'jasa_dokter',
        'share_dokter',
        'status'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function tindakan()
    {
        return $this->belongsTo(OrderTindakanMedis::class, 'order_tindakan_medis_id');
    }
}
