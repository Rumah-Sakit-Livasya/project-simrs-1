<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTable extends Model
{
    use HasFactory, SoftDeletes;

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
