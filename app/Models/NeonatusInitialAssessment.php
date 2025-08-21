<?php

// app/Models/NeonatusInitialAssessment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NeonatusInitialAssessment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'neonatus_initial_assessments';
    protected $guarded = ['id'];
    protected $casts = [
        'waktu_masuk_ruangan' => 'datetime',
        'waktu_pemeriksaan_akhir' => 'datetime',
        'info_masuk_ruangan' => 'array',
        'riwayat_kesehatan' => 'array',
        'riwayat_kelahiran' => 'array',
        'pengkajian_khusus_neonatus' => 'array',
        'keadaan_umum' => 'array',
        'penilaian_fisik' => 'array',
        'asesmen_nyeri_neonatus' => 'array',
        'masalah_keperawatan' => 'array',
        'pendidikan_kesehatan_pulang' => 'array',
        'info_bayi_pulang' => 'array',
    ];

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
