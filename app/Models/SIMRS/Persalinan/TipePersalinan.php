<?php

namespace App\Models\SIMRS\Persalinan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipePersalinan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipe_persalinan';
    protected $fillable = ['tipe', 'persentase', 'operator', 'anestesi', 'prediatric', 'room', 'observasi'];
}
