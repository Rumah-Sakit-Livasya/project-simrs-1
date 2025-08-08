<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertanggungjawabanDetail extends Model
{
    protected $guarded = ['id'];

    public function pertanggungjawaban()
    {
        return $this->belongsTo(Pertanggungjawaban::class);
    }

    public function transaksiRutin(): BelongsTo
    {
        return $this->belongsTo(TransaksiRutin::class, 'transaksi_rutin_id');
    }

    public function rncCenter(): BelongsTo
    {
        return $this->belongsTo(RncCenter::class, 'rnc_center_id');
    }
}
