<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeTransaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'tipe_transaksi';

    /**
     * Satu Tipe Transaksi bisa memiliki banyak Tagihan Pasien.
     */
    public function tagihanPasien()
    {
        return $this->hasMany(TagihanPasien::class);
    }
}
