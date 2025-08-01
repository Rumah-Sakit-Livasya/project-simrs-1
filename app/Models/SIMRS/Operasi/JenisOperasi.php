<?php

namespace App\Models\SIMRS\Operasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisOperasi extends Model
{
    use HasFactory;
    protected $table = 'jenis_operasi', $fillable = ['jenis'];

    public function tindakanOperasi()
    {
        return $this->hasMany(TindakanOperasi::class, 'jenis_operasi_id');
    }

    public function getKategoriOperasi()
    {
        return KategoriOperasi::whereIn(
            'id',
            $this->tindakanOperasi()->distinct()->pluck('kategori_operasi_id')
        )->get();
    }
}
