<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutupKunjungan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
