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
}
