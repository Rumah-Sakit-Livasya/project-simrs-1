<?php

namespace App\Models;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function penilaian_pegawais()
    {
        return $this->hasMany(PenilaianPegawai::class);
    }

    public function rekap_penilaians()
    {
        return $this->hasMany(RekapPenilaianBulanan::class);
    }

    public function attendance_outsource()
    {
        return $this->hasMany(AttendanceOutsource::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function bank_employee()
    {
        return $this->hasOne(BankEmployee::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
        // 'employee_id' adalah foreign key di tabel attendances
        // 'id' adalah primary key di tabel employees
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class);
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function day_off_requests()
    {
        return $this->hasMany(DayOffRequest::class);
    }

    public function attendance_requests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function deduction()
    {
        return $this->hasOne(Deduction::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Departement::class, 'doctors', 'employee_id', 'departement_id');
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function penilai()
    {
        return $this->hasMany(GroupPenilaian::class, 'penilai');
    }
    public function pejabat_penilai()
    {
        return $this->hasMany(GroupPenilaian::class, 'pejabat_penilai');
    }
    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }
}
