<?php

namespace App\Models\Keuangan;

use App\Models\BankPerusahaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    // protected $table = 'bank_perusahaan';
    // protected $table = 'banks';

    public function tansaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function akunKasBank()
    {
        return $this->belongsTo(ChartOfAccount::class, 'akun_kas_bank');
    }

    public function akunKliring()
    {
        return $this->belongsTo(ChartOfAccount::class, 'akun_kliring');
    }

    public function perusahaan()
    {
        return $this->hasOne(BankPerusahaan::class, 'nama', 'name')->withDefault([
            'pemilik' => '',
            'nomor' => '',
            'saldo' => 0,
            'akun_kas_bank' => null,
            'akun_kliring' => null,
            'is_aktivasi' => false,
            'is_bank' => false
        ]);
    }
}
