<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriRadiologi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_radiologi';
    protected $fillable = ['nama_kategori', 'status'];
}
