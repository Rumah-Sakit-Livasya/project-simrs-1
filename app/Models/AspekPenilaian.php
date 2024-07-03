<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AspekPenilaian extends Model
{
    use HasFactory;
    protected $fillable = ['group_penilaian_id', 'nama', 'bobot'];

    public function group_penilaian()
    {
        return $this->belongsTo(GroupPenilaian::class, 'group_penilaian_id');
    }

    public function indikator_penilaians()
    {
        return $this->hasMany(IndikatorPenilaian::class);
    }
}
