<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GantiDiagnosa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'ganti_diagnosa';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
