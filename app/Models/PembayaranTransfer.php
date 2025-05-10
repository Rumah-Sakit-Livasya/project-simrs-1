<?php

namespace App\Models;

use App\Models\SIMRS\PembayaranTagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranTransfer extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_transfer';
    protected $guarded = ['id'];

    public function pembayaran_tagihan()
    {
        return $this->belongsTo(PembayaranTagihan::class);
    }

    public function bank()
    {
        return $this->belongsTo(BankPerusahaan::class);
    }
}
