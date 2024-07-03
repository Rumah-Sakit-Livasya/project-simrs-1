<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorPenilaian extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'aspek_penilaian_id', 'max_nilai'];

    public function aspek_penilaian()
    {
        return $this->belongsTo(AspekPenilaian::class, 'aspek_penilaian_id');
    }

    public function penilaian_pegawais()
    {
        return $this->hasMany(IndikatorPenilaian::class);
    }
}
