<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class PembayaranJasaDokter extends Model
{
    protected $table = 'pembayaran_jasa_dokter';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'tahun_pajak' => 'integer',
        'pajak_persen' => 'float',
        'nominal' => 'float',
    ];

    // Dokter
    public function dokter()
    {
        return $this->belongsTo(\App\Models\SIMRS\Doctor::class, 'dokter_id');
    }

    // Bank
    public function bank()
    {
        return $this->belongsTo(\App\Models\Bank::class, 'kas_bank_id');
    }

    // Accessor - format nominal
    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        return $this->status === 'final' ? 'Sudah Dibayar' : 'Draft';
    }
}
