<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'beds';

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
