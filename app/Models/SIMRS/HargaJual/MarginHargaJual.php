<?php

namespace App\Models\SIMRS\HargaJual;

use App\Models\SIMRS\KelasRawat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarginHargaJual extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'margin_harga_jual', $fillable = ['kelas_rawat_id', 'group_penjamin_id', 'margin'];

    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }
}
