<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiAntrian extends Model
{
    protected $guarded = ['id'];

    use SoftDeletes;

    public function re()
    {
        return $this->belongsTo(FarmasiResepElektronik::class, 're_id');
    }

    public function resep()
    {
        return $this->belongsTo(FarmasiResep::class, 'resep_id');
    }
}
