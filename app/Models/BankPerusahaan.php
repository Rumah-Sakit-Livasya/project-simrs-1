<?php

namespace App\Models;

use App\Models\Keuangan\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;

class BankPerusahaan extends Model
{
    protected $table = 'bank_perusahaan';
    protected $guarded = ['id'];

    public function pembayaran_transfer()
    {
        return $this->hasMany(PembayaranTransfer::class);
    }

    protected $casts = [
        'is_aktivasi' => 'boolean',
        'is_bank' => 'boolean',
        'saldo' => 'decimal:2',
    ];

    // Definisikan relasi jika perlu
    public function akunKasBank()
    {
        return $this->belongsTo(ChartOfAccount::class, 'akun_kas_bank');
    }

    public function akunKliring()
    {
        return $this->belongsTo(ChartOfAccount::class, 'akun_kliring');
    }
    public function bank()
    {
        return $this->hasOne(Bank::class, 'name', 'nama'); // Adjust according to your actual relationship
    }
}
