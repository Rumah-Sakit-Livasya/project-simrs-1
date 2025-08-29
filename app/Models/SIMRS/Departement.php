<?php

namespace App\Models\SIMRS;

use App\Models\Employee;
use App\Models\SIMRS\Setup\TarifRegistrasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'default_dokter');
    }

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'doctor_departement', 'doctor_id', 'departement_id');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'departement_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'doctors', 'departement_id', 'employee_id');
    }

    public function doctorSchedules()
    {
        return $this->hasManyThrough(JadwalDokter::class, Doctor::class, 'departement_id', 'doctor_id', 'id', 'id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function time_tables()
    {
        return $this->hasMany(TimeTable::class);
    }

    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }

    public function grup_tindakan_medis()
    {
        return $this->hasMany(GrupTindakanMedis::class);
    }

    public function tarif_registrasi()
    {
        return $this->belongsToMany(TarifRegistrasi::class, 'tarif_registrasi_departements');
    }

    public function plasmaDisplays()
    {
        return $this->belongsToMany(PlasmaDisplayRawatJalan::class, 'departement_plasma_display');
    }
}
