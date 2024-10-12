<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengkajianDokterRajal extends Model
{
    use HasFactory;
    protected $table = 'pengkajian_dokter_rajal', $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
