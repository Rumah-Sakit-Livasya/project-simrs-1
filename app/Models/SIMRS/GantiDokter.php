<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GantiDokter extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'ganti_dokters';

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'id', 'registration_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id', 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
