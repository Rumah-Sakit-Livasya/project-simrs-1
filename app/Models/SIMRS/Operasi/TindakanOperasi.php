<?php

namespace App\Models\SIMRS\Operasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakanOperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tindakan_operasi', $fillable = ['jenis_operasi_id', 'kategori_operasi_id', 'kode_operasi', 'nama_operasi', 'nama_billing'];

    public function jenisOperasi()
    {
        return $this->belongsTo(JenisOperasi::class, 'jenis_operasi_id');
    }

    public function kategoriOperasi()
    {
        return $this->belongsTo(KategoriOperasi::class, 'kategori_operasi_id');
    }

    public function scopeByJenisOperasi($query, $jenisId)
    {
        return $query->where('jenis_operasi_id', $jenisId);
    }

    // Scope untuk filter berdasarkan kategori operasi
    public function scopeByKategoriOperasi($query, $kategoriId)
    {
        return $query->where('kategori_operasi_id', $kategoriId);
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return $this->nama_operasi . ' (' . $this->kode_operasi . ')';
    }

    public function tarif()
    {
        return $this->hasMany(TarifOperasi::class, 'tindakan_operasi_id');
    }
}
