<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranAsuransiDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_asuransi_detail';

    protected $fillable = [
        'pembayaran_asuransi_id',
        'konfirmasi_asuransi_id',
        'dibayar',
    ];

    /**
     * Relasi ke header pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(PembayaranAsuransi::class, 'pembayaran_asuransi_id');
    }

    /**
     * Relasi ke konfirmasi asuransi (invoice)
     */
    public function konfirmasiAsuransi()
    {
        return $this->belongsTo(KonfirmasiAsuransi::class, 'konfirmasi_asuransi_id');
    }
}
