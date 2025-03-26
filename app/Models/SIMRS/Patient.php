<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    protected $guarded = ['id'];

    use HasFactory, SoftDeletes;

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class);
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
}
