<?php

namespace App\Models\SIMRS;

use App\Models\Employee;
use App\Models\RegistrationOTC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'doctor_departement', 'doctor_id', 'departement_id');
    }

    public function department_from_doctors()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function schedules()
    {
        return $this->hasMany(JadwalDokter::class, 'doctor_id');
    }

    public function time_tables()
    {
        return $this->hasMany(TimeTable::class);
    }

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }

    public function registration_otc()
    {
        return $this->hasMany(RegistrationOTC::class, 'doctor_id');
    }

    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }
    public function getNameAttribute()
    {
        return $this->employee->fullname ?? '-';
    }
}
