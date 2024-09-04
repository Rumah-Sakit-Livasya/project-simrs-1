<?php

namespace App\Models\SIMRS;

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

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function time_tables()
    {
        return $this->hasMany(TimeTable::class);
    }
}
