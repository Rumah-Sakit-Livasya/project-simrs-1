<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmasiReturResepItems extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function retur()
    {
        return $this->belongsTo(FarmasiReturResep::class, 'retur_id');
    }

    public function ri(){
        return $this->belongsTo(FarmasiResepItems::class, 'ri_id');
    }
}
