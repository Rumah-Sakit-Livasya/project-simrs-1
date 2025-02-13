<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Bilingan extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = ['registration_id', 'status', 'is_paid'];
    protected $table = 'bilingan';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function pembayaran_tagihan()
    {
        return $this->hasOne(PembayaranTagihan::class);
    }

    public function down_payment()
    {
        return $this->hasOne(DownPayment::class);
    }

    public function tagihan_pasien()
    {
        return $this->belongsToMany(TagihanPasien::class, 'bilingan_tagihan_pasien', 'tagihan_pasien_id', 'bilingan_id');
    }
}
