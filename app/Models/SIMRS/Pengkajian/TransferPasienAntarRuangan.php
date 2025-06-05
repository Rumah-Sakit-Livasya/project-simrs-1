<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\Signature;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class TransferPasienAntarRuangan extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'transfer_pasien_antar_ruangan';

    // Define the inverse relationship to Registration
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    // Pengkajian.php
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function signaturePengirim()
    {
        return $this->signatures()->where('role', 'pengirim')->latest()->first();
    }

    public function signaturePenerima()
    {
        return $this->signatures()->where('role', 'penerima')->latest()->first();
    }

    public function signaturePengirimBalik()
    {
        return $this->signatures()->where('role', 'pengirim_balik')->latest()->first();
    }

    public function signaturePenerimaBalik()
    {
        return $this->signatures()->where('role', 'penerima_balik')->latest()->first();
    }
}
