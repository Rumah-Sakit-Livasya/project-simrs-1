<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorInitialAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'doctor_initial_assessments';
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_dilayani' => 'datetime',
        'tanda_vital' => 'array',
        'anamnesis' => 'array',
        'gambar_anatomi' => 'array',
        'edukasi' => 'array',
        'evaluasi_penyakit' => 'array',
        'rencana_tindak_lanjut_pasien' => 'array',
    ];

    /**
     * Relasi polimorfik untuk tanda tangan.
     */
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
