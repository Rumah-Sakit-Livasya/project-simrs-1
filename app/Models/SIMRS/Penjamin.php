<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Penjamin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['is_bpjs'];

    // protected $table = 'penjamin';

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

    public function getIsBpjsAttribute()
    {
        $group = $this->group_penjamin;

        return $group && Str::contains(Str::lower($group->name), 'bpjs');
    }
}
