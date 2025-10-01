<?php

namespace App\Models\SIMRS;

use App\Models\PembayaranCreditCard;
use App\Models\PembayaranTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PembayaranTagihan extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'pembayaran_tagihan';

    public function bilingan()
    {
        return $this->belongsTo(Bilingan::class);
    }

    public function pembayaran_transfer()
    {
        return $this->belongsTo(PembayaranTransfer::class);
    }

    public function pembayaran_credit_card()
    {
        return $this->hasOne(PembayaranCreditCard::class, 'pembayaran_tagihan_id');
    }
}
