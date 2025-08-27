<?php

namespace App\Models;

use App\Models\Inventaris\RoomMaintenance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kunjungan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi baru ke RoomMaintenance
    public function roomMaintenance()
    {
        return $this->belongsTo(RoomMaintenance::class);
    }

    // Relasi baru ke DokumentasiKunjungan
    public function dokumentasi()
    {
        return $this->hasMany(DokumentasiKunjungan::class);
    }
}
