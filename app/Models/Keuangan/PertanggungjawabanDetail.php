<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class PertanggungjawabanDetail extends Model
{
    protected $guarded = ['id'];

    public function pertanggungjawaban()
    {
        return $this->belongsTo(Pertanggungjawaban::class);
    }
}
