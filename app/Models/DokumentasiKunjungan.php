<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumentasiKunjungan extends Model
{
    use HasFactory;
    protected $fillable = ['kunjungan_id', 'file_path'];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
