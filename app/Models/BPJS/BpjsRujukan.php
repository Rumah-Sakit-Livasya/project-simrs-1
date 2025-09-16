<?php

namespace App\Models\BPJS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BpjsRujukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bpjs_rujukans';

    protected $fillable = [
        'registration_id',
        'no_rujukan',
        'tgl_rujukan',
        'ppk_dirujuk_kode',
        'ppk_dirujuk_nama',
        'diagnosa_kode',
        'diagnosa_nama',
        'tipe_rujukan',
        'catatan'
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class);
    }
}
