<?php

namespace App\Models\Keuangan;

use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TindakanMedis;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class KonfirmasiAsuransi extends Model
{
    protected $table = 'konfirmasi_asuransi';

    protected $fillable = [
        'penjamin_id',
        'registration_id',
        'invoice',
        'jumlah',
        'diskon',
        'tanggal',
        'jatuh_tempo',
        'pembayaran_id',
        'status_pembayaran',
        'tanggal_pembayaran',
        'keterangan',
        'created_by',
        'updated_by',
        'tagihan_ke',
        'status',
        'sisa_tagihan',
        'total_dibayar',
        'is_lunas',
        'last_pembayaran_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'jatuh_tempo' => 'date',
        'tanggal_pembayaran' => 'date',
        'is_lunas' => 'boolean',
        'sisa_tagihan' => 'decimal:2',
        'total_dibayar' => 'decimal:2',
    ];

    // 🔄 Relasi
    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Registration::class,
            'id', // Foreign key di Registration
            'id', // Foreign key di Patient
            'registration_id', // Local key di KonfirmasiAsuransi
            'patient_id' // Local key di Registration
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pembayaran_detail()
    {
        return $this->hasMany(PembayaranAsuransiDetail::class, 'konfirmasi_asuransi_id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranAsuransi::class, 'pembayaran_id');
    }

    public function lastPembayaran()
    {
        return $this->belongsTo(PembayaranAsuransi::class, 'last_pembayaran_id');
    }

    // 🔢 Menghitung keterlambatan (days overdue)
    public function getDaysOverdueAttribute()
    {
        if (!$this->jatuh_tempo) return 0;
        return Carbon::now()->diffInDays(Carbon::parse($this->jatuh_tempo), false);
    }

    public function tindakanMedis()
    {
        return $this->belongsTo(TindakanMedis::class, 'tindakan_medis_id');
    }

    public function tagihan_pasien()
    {
        return $this->hasManyThrough(
            TagihanPasien::class,
            Registration::class,
            'id', // Foreign key di Registration
            'registration_id', // Foreign key di TagihanPasien
            'registration_id', // Foreign key di KonfirmasiAsuransi
            'id'  // Local key di Registration
        );
    }
}
