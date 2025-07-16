<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dokter';
    protected $guarded = ['id'];

    // Relasi ke model Doctor (pivot)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
