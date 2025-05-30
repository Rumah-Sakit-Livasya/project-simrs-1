<?php

namespace App\Models\SIMRS\Pelayanan;

use App\Models\Signature;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Triage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'triage';

    protected $fillable = [
        'tgl_masuk',
        'jam_masuk',
        'jam_dilayani',
        'pr',
        'bp',
        'body_height',
        'bmi',
        'lingkar_dada',
        'sp02',
        'rr',
        'temperatur',
        'body_weight',
        'kat_bmi',
        'lingkar_perut',
        'auto_anamnesa',
        'allo_anamnesa',
        'airway_merah',
        'airway_kuning',
        'airway_hijau',
        'breathing_merah',
        'breathing_kuning',
        'breathing_hijau',
        'circulation_merah',
        'circulation_kuning',
        'circulation_hijau',
        'disability',
        'kesimpulan',
        'daa_hitam',
        'registration_id'
    ];

    protected $casts = [
        'airway_merah' => 'array',
        'airway_kuning' => 'array',
        'airway_hijau' => 'array',
        'breathing_merah' => 'array',
        'breathing_kuning' => 'array',
        'breathing_hijau' => 'array',
        'circulation_merah' => 'array',
        'circulation_kuning' => 'array',
        'circulation_hijau' => 'array',
        'disability' => 'array',
        'kesimpulan' => 'array',
    ];

    /**
     * Relasi ke Registration
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function signature()
    {
        return $this->morphOne(Signature::class, 'signable');
    }
}
