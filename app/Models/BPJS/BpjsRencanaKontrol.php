<?php

namespace App\Models\BPJS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BpjsRencanaKontrol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bpjs_rencana_kontrols';

    protected $fillable = [
        'registration_id',
        'no_surat_kontrol',
        'tgl_rencana_kontrol',
        'poli_kontrol_kode',
        'poli_kontrol_nama',
        'dokter_kode',
        'dokter_nama',
        'jenis_kontrol'
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class);
    }
}
