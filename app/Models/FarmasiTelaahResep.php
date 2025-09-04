<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmasiTelaahResep extends Model
{
    protected $guarded = ['id'];

    public function resep()
    {
        return $this->belongsTo(FarmasiResep::class, "resep_id");
    }

    // Pengkajian.php
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function getSignaturePengirimAttribute()
    {
        return $this->signatures()->where('role', 'pengirim')->latest()->first();
    }
    public function getSignaturePenerimaAttribute()
    {
        return $this->signatures()->where('role', 'penerima')->latest()->first();
    }
    public function getSignaturePengirimBalikAttribute()
    {
        return $this->signatures()->where('role', 'pengirim_balik')->latest()->first();
    }
    public function getSignaturePenerimaBalikAttribute()
    {
        return $this->signatures()->where('role', 'penerima_balik')->latest()->first();
    }
}
