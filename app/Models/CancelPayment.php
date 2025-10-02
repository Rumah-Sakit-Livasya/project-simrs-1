<?php

namespace App\Models;

use App\Models\SIMRS\Bilingan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancelPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bilingan_id',
        'user_id',
        'otorisasi_id',
        'tgl_batal',
        'catatan',
    ];

    public function bilingan()
    {
        return $this->belongsTo(Bilingan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function otorisasiBy()
    {
        return $this->belongsTo(User::class, 'otorisasi_id');
    }
}
