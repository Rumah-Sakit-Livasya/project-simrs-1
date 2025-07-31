<?php

namespace App\Models\keuangan;

use App\Models\keuangan\PembayaranJasaDokter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenambahanNonPajak extends Model
{
    use HasFactory;

    protected $table = 'penambahan_non_pajaks';
    protected $fillable = [
        'pembayaran_jasa_dokter_id',
        'keterangan',
        'akun',
        'cost_revenue',
        'jasa_dokter',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranJasaDokter::class, 'pembayaran_jasa_dokter_id');
    }
}
