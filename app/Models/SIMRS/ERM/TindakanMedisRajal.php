<?php

namespace App\Models\SIMRS\ERM;

use Illuminate\Database\Eloquent\Model;

class TindakanMedisRajal extends Model
{
    protected $table = 'tindakan_medis_rajal', $fillable = ['registration_id', 'doctor_id', 'tindakan_medis_id', 'kelas_rawat_id', 'qty', 'total_harga', 'user_entry'];
}
