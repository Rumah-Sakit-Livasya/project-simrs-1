<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'rooms';

    public function beds()
    {
        return $this->hasMany(Bed::class, 'room_id', 'id');
    }

    public function bedTambahan()
    {
        return $this->hasMany(Bed::class, 'room_id', 'id')
            ->where('beds.is_tambahan', 1);
    }

    public function bedBor()
    {
        return $this->hasMany(Bed::class, 'room_id', 'id')
            ->where('beds.is_tambahan', 0);
    }

    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id', 'id');
    }
}
