<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SIMRS\Registration;

class HospitalInfectionSurveillance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hospital_infection_surveillances';

    protected $fillable = [
        'registration_id',
        'user_id',
        'tgl_masuk',
        'cara_dirawat',
        'diagnosa_masuk',
        'pindah_ke_ruangan',
        'tgl_pindah',
        'faktor_resiko',
        'faktor_penyakit',
        'tindakan_operasi',
        'komplikasi_infeksi',
        'pemakaian_antimikroba',
        'tgl_keluar',
        'keterangan_keluar',
        'pindah_rs_lain',
        'diagnosa_akhir'
    ];

    // Ini sangat penting! Otomatis mengubah JSON dari/ke array
    protected $casts = [
        'tgl_masuk' => 'datetime',
        'tgl_pindah' => 'date',
        'tgl_keluar' => 'date',
        'faktor_resiko' => 'array',
        'faktor_penyakit' => 'array',
        'tindakan_operasi' => 'array',
        'komplikasi_infeksi' => 'array',
        'pemakaian_antimikroba' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
