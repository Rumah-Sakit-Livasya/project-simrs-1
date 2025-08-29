<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BedPatient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bed_patient';
    protected $guarded = ['id'];


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Relasi ke Bed
    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }
}
