<?php

namespace App\Models\SIMRS\Persalinan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPersalinan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_persalinan';
    protected $fillable = ['nama', 'is_aktif'];
}
