<?php

namespace App\Models\SIMRS\Laboratorium;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'kategori_laboratorium';
    protected $fillable = ['nama_kategori', 'status'];
}
