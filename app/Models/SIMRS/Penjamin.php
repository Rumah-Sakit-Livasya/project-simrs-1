<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjamin extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'penjamin';

    public function patient()
    {
        return $this->hasMany(Patient::class);
    }

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }

    public function group_penjamin()
    {
        return $this->belongsTo(GroupPenjamin::class);
    }
}
