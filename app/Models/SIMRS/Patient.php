<?php

namespace App\Models\SIMRS;

use App\Models\OrderRadiologi;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    protected $guarded = ['id'];

    use HasFactory, SoftDeletes;

    public function setDateOfBirthAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            $this->attributes['date_of_birth'] = $value;
        } else if ($value) {
            $this->attributes['date_of_birth'] = \Carbon\Carbon::parse($value);
        }
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function ethnic()
    {
        return $this->belongsTo(Ethnic::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'ward', 'id');
    }

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }

    public function bed()
    {
        return $this->hasOne(Bed::class);
    }

    public function beds()
    {
        return $this->belongsToMany(Bed::class, 'bed_patient')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Relasi untuk mengambil semua order lab pasien
     * melalui tabel registrations.
     */
    public function orderLaboratorium()
    {
        return $this->hasManyThrough(
            OrderLaboratorium::class,
            Registration::class,
            'patient_id',
            'registration_id',
            'id',
            'id'
        );
    }

    /**
     * Relasi untuk mengambil semua order radiologi pasien
     * melalui tabel registrations.
     */
    public function orderRadiologi()
    {
        return $this->hasManyThrough(
            OrderRadiologi::class,
            Registration::class,
            'patient_id',
            'registration_id',
            'id',
            'id'
        );
    }

    /**
     * Relasi untuk dokumen pasien
     */
    public function documents()
    {
        return $this->hasMany(PatientDocument::class);
    }

    /**
     * Get active registration
     */
    public function activeRegistration()
    {
        return $this->hasOne(Registration::class)->where('status', 'aktif')->latest();
    }

    /**
     * Get total kunjungan
     */
    public function getTotalVisitsAttribute()
    {
        return $this->registration()->count();
    }

    /**
     * Get umur pasien
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Scope untuk pencarian pasien
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('medical_record_number', 'LIKE', "%{$search}%")
                ->orWhere('nik', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope untuk pasien aktif (yang memiliki registrasi aktif)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('registration', function ($q) {
            $q->where('status', 'aktif');
        });
    }
}
