<?php

namespace App\Models\SIMRS\Pengkajian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengkajianDokterRajal extends Model
{
    use HasFactory;
    protected $table = 'pengkajian_dokter_rajal', $guarded = ['id'];
}
