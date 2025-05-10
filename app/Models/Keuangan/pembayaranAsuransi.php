<?php

namespace App\Models\Keuangan;

use App\Models\Keuangan\PembayaranAsuransiDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranAsuransi extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_asuransi';

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'penjamin_id',
        'bank_id',
        'jumlah',
        'status',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    // Relasi ke penjamin (asuransi)
    public function penjamin()
    {
        return $this->belongsTo(\App\Models\SIMRS\Penjamin::class, 'penjamin_id');
    }

    // Relasi ke bank
    public function bank()
    {
        return $this->belongsTo(\App\Models\Bank::class, 'bank_id');
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Relasi ke user yang mengubah terakhir
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // Relasi ke detail pembayaran
    public function details()
    {
        return $this->hasMany(PembayaranAsuransiDetail::class, 'pembayaran_asuransi_id');
    }
}
