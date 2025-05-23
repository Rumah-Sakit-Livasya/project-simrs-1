<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaDokterManual extends Model
{
    use HasFactory;

    protected $table = 'jasa_dokter_manuals';
    protected $fillable = [
        'pembayaran_jasa_dokter_id',
        'keterangan',
        'akun',
        'cost_revenue',
        'jasa_dokter',
        'jkp_tambahan',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranJasaDokter::class, 'pembayaran_jasa_dokter_id');
    }
}
