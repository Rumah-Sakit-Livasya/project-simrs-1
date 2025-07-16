<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EWSObstetri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ews_obstetri';

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class); // Hubungan dengan model Registration
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Hubungan dengan model Registration
    }
}
