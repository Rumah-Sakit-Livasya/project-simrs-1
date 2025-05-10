<?php

namespace App\Models;

use App\Models\Keuangan\PembayaranAsuransi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranAsuransiDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_asuransi_detail';

    protected $fillable = [
        'pembayaran_asuransi_id',
        'konfirmasi_asuransi_id',
        'jumlah_dibayar',
        'keterangan',
    ];

    // Relasi ke header pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(PembayaranAsuransi::class, 'pembayaran_asuransi_id');
    }

    // Relasi ke konfirmasi asuransi (tagihan)
    public function konfirmasi()
    {
        return $this->belongsTo(\App\Models\Keuangan\KonfirmasiAsuransi::class, 'konfirmasi_asuransi_id');
    }
}
