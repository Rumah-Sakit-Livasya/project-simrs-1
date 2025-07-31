<?php

namespace App\Models\Keuangan;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\OrderTindakanMedis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaDokter extends Model
{
    use HasFactory;

    protected $table = 'jasa_dokter';
    protected $fillable = [
        'registration_id',
        'dokter_id',
        'order_tindakan_medis_id',
        'tagihan_pasien_id',
        'nama_tindakan',
        'nominal',
        'diskon',
        'ppn_persen',
        'jkp',
        'share_dokter',
        'status',
        'ap_number',
        'ap_date',
        'bill_date'
    ];

    protected $casts = [
        'ap_date' => 'date',
        'bill_date' => 'date',
        'nominal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ppn_persen' => 'decimal:2',
        'jkp' => 'decimal:2',
        'jasa_dokter' => 'decimal:2',
        'share_dokter' => 'decimal:2',
    ];
    public function tagihanPasien()
    {
        return $this->belongsTo(\App\Models\SIMRS\TagihanPasien::class, 'tagihan_pasien_id');
    }

    public function registration()
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class, 'registration_id');
    }

    public function bilingan()
    {
        return $this->belongsTo(\App\Models\SIMRS\Bilingan::class, 'bilingan_id');
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\SIMRS\Doctor::class, 'dokter_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Doctor::class, 'dokter_id');
    }
}
