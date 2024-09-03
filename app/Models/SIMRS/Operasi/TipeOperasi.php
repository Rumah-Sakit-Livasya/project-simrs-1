<?php

namespace App\Models\SIMRS\Operasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeOperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipe_operasi', $fillable = ['tipe', 'operator', 'anestesi', 'resusitator', 'dokter_tambahan', 'alat', 'ruangan'];
}
