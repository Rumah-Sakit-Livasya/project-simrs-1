<?php

namespace App\Models\Keuangan;

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
}
