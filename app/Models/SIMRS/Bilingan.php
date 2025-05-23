<?php

namespace App\Models\SIMRS;

use App\Events\BillingFinalized;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Bilingan extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'bilingan';

    protected static function booted()
    {
        static::updated(function ($billing) {
            if ($billing->isDirty('status') && strtolower($billing->status) === 'final') {
                event(new BillingFinalized($billing));
            }
        });
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function pembayaran_tagihan()
    {
        return $this->hasOne(PembayaranTagihan::class);
    }

    public function down_payment()
    {
        return $this->hasMany(DownPayment::class);
    }

    public function tagihan_pasien()
    {
        return $this->belongsToMany(TagihanPasien::class, 'bilingan_tagihan_pasien');
    }

    public function order_tindakan_medis()
    {
        return $this->belongsToMany(OrderTindakanMedis::class, 'order_tindakan_medis_bilingan');
    }
}
