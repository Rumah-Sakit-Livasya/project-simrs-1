<?php

namespace App\Models\SIMRS;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function time_tables()
    {
        return $this->hasMany(TimeTable::class);
    }

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }
}
