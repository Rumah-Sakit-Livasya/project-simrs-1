<?php

namespace App\Models\SIMRS;

use App\Models\Keuangan\JasaDokter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TagihanPasien extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'tagihan_pasien';

    public function bilingan()
    {
        return $this->belongsToMany(Bilingan::class, 'bilingan_tagihan_pasien');
    }



    public function bilinganSatu()
    {
        return $this->belongsTo(Bilingan::class, 'bilingan_id');
    }

    public function getBillDateAttribute()
    {
        return $this->bilinganSatu->created_at ?? null;
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tindakan_medis()
    {
        return $this->belongsTo(TindakanMedis::class, 'tindakan_medis_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function jasaDokter()
    {
        return $this->hasOne(JasaDokter::class, 'tagihan_pasien_id');
    }

    public function jasaDokterWithTrashed()
    {
        return $this->hasOne(\App\Models\keuangan\JasaDokter::class, 'tagihan_pasien_id')->withTrashed();
    }

    public function patient()
    {
        return $this->hasOneThrough(
            \App\Models\SIMRS\Patient::class,
            \App\Models\SIMRS\Registration::class,
            'id', // Foreign key on registrations table...
            'id', // Foreign key on patients table...
            'registration_id', // Local key on tagihan_pasien table...
            'patient_id' // Local key on registrations table...
        );
    }
}
