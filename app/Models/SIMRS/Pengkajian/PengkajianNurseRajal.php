<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class PengkajianNurseRajal extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'pengkajian_nurse_rajal';
    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_dilayani' => 'date',
    ];

    // Define the inverse relationship to Registration
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function pengkajian_dokter_rajal()
    {
        return $this->hasOne(PengkajianDokterRajal::class, 'registration_id', 'registration_id');
    }
}
