<?php

namespace App\Models\SIMRS\Operasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriOperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_operasi', $fillable = ['nama_kategori', 'urutan'];

    public function tindakanOperasi()
    {
        return $this->hasMany(TindakanOperasi::class, 'kategori_operasi_id');
    }

    public function jenisOperasi()
    {
        return $this->belongsTo(JenisOperasi::class, 'jenis_operasi_id');
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
