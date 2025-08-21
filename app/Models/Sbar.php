<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes
use App\Models\SIMRS\CPPT\CPPT; // Sesuaikan path jika perlu
use App\Models\User;

class Sbar extends Model
{
    use HasFactory, SoftDeletes; // Gunakan SoftDeletes

    protected $guarded = ['id'];


    public function cppt()
    {
        return $this->belongsTo(CPPT::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function getSignaturePemberiAttribute()
    {
        return $this->signatures()->where('role', 'pemberi')->latest()->first();
    }

    public function getSignaturePenerimaAttribute()
    {
        return $this->signatures()->where('role', 'penerima')->latest()->first();
    }
}
