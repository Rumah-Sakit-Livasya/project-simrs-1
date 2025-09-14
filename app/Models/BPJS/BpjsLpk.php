<?php

namespace App\Models\BPJS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BpjsLpk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bpjs_lpks';

    protected $fillable = [
        'registration_id',
        'no_lpk',
        'tgl_lpk',
        'total_biaya',
        'status_klaim'
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class);
    }
}
