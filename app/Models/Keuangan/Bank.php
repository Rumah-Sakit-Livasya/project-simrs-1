<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'bank_perusahaan';

    public function tansaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
