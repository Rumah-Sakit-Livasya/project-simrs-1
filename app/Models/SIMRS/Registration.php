<?php

namespace App\Models\SIMRS;

use App\Models\SIMRS\BatalRegister;
use App\Models\Employee;
use App\Models\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Define the relationship to PengkajianNurseRajal
    public function pengkajian_nurse_rajal()
    {
        return $this->hasOne(PengkajianNurseRajal::class, 'registration_id');
    }

    public function pengkajian_dokter_rajal()
    {
        return $this->hasOne(PengkajianDokterRajal::class, 'registration_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function batal_registration()
    {
        return $this->hasOne(BatalRegister::class);
    }

    public function tutup_kunjungan()
    {
        return $this->hasOne(TutupKunjungan::class);
    }

    public function batal_keluar()
    {
        return $this->hasOne(BatalKeluar::class);
    }

    public function ganti_dokter()
    {
        return $this->hasOne(GantiDokter::class);
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->registrasi = $model->generateNomorRegistrasi();
        // });
    }

    // public function generateNomorRegistrasi()
    // {
    //     $date = Carbon::now();
    //     $year = $date->format('y');
    //     $month = $date->format('m');
    //     $day = $date->format('d');

    //     $count = Registration::whereDate('created_at', $date->toDateString())->count() + 1;
    //     $count = str_pad($count, 4, '0', STR_PAD_LEFT);

    //     return $year . $month . $day . $count;
    // }
}
