<?php

namespace App\Models\SIMRS;

use App\Models\Keuangan\JasaDokter;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\Operasi\TindakanOperasi;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Facades\Log;
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
    public function order_radiologi()
    {
        return $this->belongsTo(OrderRadiologi::class, 'order_radiologi_id');
    }
    protected static function booted()
    {
        static::creating(function ($tagihan) {
            if (str_contains(strtolower($tagihan->tipe_tagihan ?? ''), 'radiologi') || str_contains(strtolower($tagihan->tagihan ?? ''), 'radiologi')) {

                // Buat stack trace menggunakan Exception, tapi jangan di-throw
                $e = new \Exception();

                // Tulis jejak lengkap ke file log Laravel
                Log::info('--- JEJAK PEMBUATAN TAGIHAN RADIOLOGI DITEMUKAN ---');
                Log::info('Data Tagihan:', $tagihan->toArray());
                Log::info($e->getTraceAsString());
                Log::info('--- AKHIR JEJAK ---');
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tindakan_medis()
    {
        return $this->belongsTo(TindakanMedis::class, 'tindakan_medis_id');
    }

    public function tindakanOperasi()
    {
        return $this->belongsTo(TindakanOperasi::class);
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
        return $this->hasOne(\App\Models\Keuangan\JasaDokter::class, 'tagihan_pasien_id')->withTrashed();
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

    public function doctor_visit()
    {
        return $this->belongsTo(DoctorVisit::class);
    }

    public function order_alat_medis()
    {
        return $this->belongsTo(OrderAlatMedis::class);
    }
}
