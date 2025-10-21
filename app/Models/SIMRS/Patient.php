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

    // protected $casts = [
    //     'date_of_birth' => 'date',
    // ];

    use HasFactory, SoftDeletes;

    // public function setDateOfBirthAttribute($value)
    // {
    //     $this->attributes['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    // }

    public function setDateOfBirthAttribute($value)
    {
        // Jika nilai yang diberikan SUDAH berupa instance Carbon, langsung gunakan.
        if ($value instanceof \Carbon\Carbon) {
            $this->attributes['date_of_birth'] = $value;
        }
        // Jika BUKAN (misalnya string), baru kita parse.
        else if ($value) { // Pastikan value tidak null/kosong sebelum parsing
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
        // Argumen kedua ('ward') adalah nama foreign key di tabel 'patients'.
        // Argumen ketiga ('id') adalah nama primary key di tabel 'kelurahans'.
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
            'patient_id',             // Foreign key di model perantara (registrations)
            'registration_id',        // Foreign key di model tujuan (order_laboratoriums)
            'id',                     // Local key di model ini (patients)
            'id'                      // Local key di model perantara (registrations)
        );
    }

    /**
     * Relasi untuk mengambil semua order radiologi pasien
     * melalui tabel registrations.
     */
    public function orderRadiologi()
    {
        return $this->hasManyThrough(
            OrderRadiologi::class,    // Model tujuan
            Registration::class,      // Model perantara
            'patient_id',             // Foreign key di model perantara (registrations)
            'registration_id',        // Foreign key di model tujuan (order_radiologis)
            'id',                     // Local key di model ini (patients)
            'id'                      // Local key di model perantara (registrations)
        );
    }
}
