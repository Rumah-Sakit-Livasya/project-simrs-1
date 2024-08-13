<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeLaboratorium extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipe_laboratorium';
    protected $fillable = ['nama_tipe', 'status'];
}
