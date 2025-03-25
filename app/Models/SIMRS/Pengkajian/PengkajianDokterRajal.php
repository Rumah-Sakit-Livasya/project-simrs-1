<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PengkajianDokterRajal extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $table = 'pengkajian_dokter_rajal', $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function pengkajian_nurse_rajal()
    {
        return $this->hasOne(PengkajianNurseRajal::class, 'registration_id', 'registration_id');
    }
}
