<?php

namespace App\Models;

use App\Models\SIMRS\PembayaranTagihan;
use Illuminate\Database\Eloquent\Model;

class PembayaranCreditCard extends Model
{
    protected $table = 'pembayaran_credit_card';
    protected $guarded = ['id'];

    public function pembayaran_tagihan()
    {
        return $this->belongsTo(PembayaranTagihan::class);
    }
}
