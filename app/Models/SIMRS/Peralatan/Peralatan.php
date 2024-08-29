<?php

namespace App\Models\SIMRS\Peralatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peralatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peralatan';
    protected $fillable = ['kode', 'nama', 'satuan_pakai', 'is_req_dokter'];
}
