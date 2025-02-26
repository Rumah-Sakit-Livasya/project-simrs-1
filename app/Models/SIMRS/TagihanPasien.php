<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TagihanPasien extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'tagihan_pasien';

    public function bilingan()
    {
        return $this->belongsToMany(Bilingan::class, 'bilingan_tagihan_pasien');
    }
}
