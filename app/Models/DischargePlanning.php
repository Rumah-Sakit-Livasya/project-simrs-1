<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DischargePlanning extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'discharge_plannings';

    protected $fillable = [
        'registration_id',
        'user_id',
        'skrining_faktor_resiko',
        'rencana_perawatan_rumah',
        'hal_diperhatikan',
        'waktu_penjelasan'
    ];

    // Otomatis mengubah JSON dari/ke array
    protected $casts = [
        'skrining_faktor_resiko' => 'array',
        'rencana_perawatan_rumah' => 'array',
        'hal_diperhatikan' => 'array',
        'waktu_penjelasan' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class);
    }

    // Tambahkan relasi signature jika form ini akan memiliki TTD
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
