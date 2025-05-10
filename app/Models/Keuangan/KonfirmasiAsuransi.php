<?php

namespace App\Models\Keuangan;

use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KonfirmasiAsuransi extends Model
{
    protected $table = 'konfirmasi_asuransi';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Registration::class,
            'id', // Foreign key on Registration table
            'id', // Foreign key on Patient table
            'registration_id', // Local key on KonfirmasiAsuransi table
            'patient_id' // Local key on Registration table
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
