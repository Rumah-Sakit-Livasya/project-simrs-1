<?php

namespace App\Models\Keuangan;

use App\Models\BankPerusahaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $table = 'petty_cash';

    protected $fillable = [
        'tanggal',
        'kas_id',
        'keterangan',
        'status',
    ];

    /**
     * Relasi ke detail petty cash (1 : N)
     */
    public function details()
    {
        return $this->hasMany(PettyCashDetail::class, 'petty_cash_id');
    }

    /**
     * Relasi ke kas/bank
     * (asumsi tabel: akun_kas_bank)
     */
    public function kas()
    {
        return $this->belongsTo(BankPerusahaan::class, 'kas_id');
    }
}
