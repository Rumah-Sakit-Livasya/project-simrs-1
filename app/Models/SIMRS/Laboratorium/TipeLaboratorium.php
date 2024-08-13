<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'tipe_laboratorium';
    protected $fillable = ['nama_tipe', 'status'];
}
