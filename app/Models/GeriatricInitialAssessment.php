<?php

// app/Models/GeriatricInitialAssessment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeriatricInitialAssessment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'geriatric_initial_assessments';
    protected $guarded = ['id'];
    protected $casts = [
        'waktu_masuk_ruangan' => 'datetime',
        'info_masuk_ruangan' => 'array',
        'pemeriksaan_dibawa' => 'array',
        'obat_dibawa' => 'array',
        'riwayat_kesehatan' => 'array',
        'riwayat_kesehatan_lalu' => 'array',
        'riwayat_alergi' => 'array',
        'riwayat_kesehatan_keluarga' => 'array',
        'riwayat_psikososial' => 'array',
        'riwayat_komunikasi' => 'array',
        'riwayat_kebudayaan' => 'array',
        'respon_emosi_kognitif' => 'array',
        'informasi_diinginkan' => 'array',
        'nutrisi' => 'array',
        'eliminasi' => 'array',
        'personal_hygiene' => 'array',
        'istirahat_tidur' => 'array',
        'aktivitas_latihan' => 'array',
        'neuro_cerebral' => 'array',
        'tingkat_kesadaran' => 'array',
        'pemeriksaan_fisik' => 'array',
        'asesmen_nyeri' => 'array',
        'status_fungsional' => 'array',
        'resiko_jatuh_lansia' => 'array',
        'masalah_keperawatan' => 'array',
    ];

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
