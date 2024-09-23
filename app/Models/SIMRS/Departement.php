<?php

namespace App\Models\SIMRS;

use App\Models\Employee;
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

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
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
}
