<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiResepResponse extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function re()
    {
        return $this->belongsTo(FarmasiResepElektronik::class, 're_id');
    }

    public function resep()
    {
        if ($this->resep_id != null) {
            return $this->belongsTo(FarmasiResep::class, 'resep_id');
        } else {
            return $this->re->resep();
        }
    }

    public function inputer()
    {
        return $this->belongsTo(User::class, 'input_resep_user_id');
    }

    public function penyiap()
    {
        return $this->belongsTo(User::class, 'penyiapan_user_id');
    }

    public function raciker()
    {
        return $this->belongsTo(User::class, 'racik_user_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikasi_user_id');
    }

    public function penyerah()
    {
        return $this->belongsTo(User::class, 'penyerahan_user_id');
    }
}
