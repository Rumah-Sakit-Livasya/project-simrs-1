<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengkajianNurseRajal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'pengkajian_nurse_rajal';

    // Define the inverse relationship to Registration
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
