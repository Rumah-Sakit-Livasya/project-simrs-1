<?php

namespace App\Models\SIMRS;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    protected $guarded = ['id'];

    // protected $casts = [
    //     'date_of_birth' => 'date',
    // ];

    use HasFactory, SoftDeletes;

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function ethnic()
    {
        return $this->belongsTo(Ethnic::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
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
