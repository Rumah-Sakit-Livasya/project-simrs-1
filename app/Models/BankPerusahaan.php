<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankPerusahaan extends Model
{
    protected $table = 'bank_perusahaan';
    protected $guarded = ['id'];

    public function pembayaran_transfer()
    {
        return $this->hasMany(PembayaranTransfer::class);
    }
}
