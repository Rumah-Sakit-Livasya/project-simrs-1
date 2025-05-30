<?php

namespace App\Models\SIMRS;

use App\Models\Signature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssesmentKeperawatanGadar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assesment_keperawatan_gadar';

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class); // Hubungan dengan model Registration
    }

    public function signature()
    {
        return $this->morphOne(Signature::class, 'signable');
    }
}
