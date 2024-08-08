<?php

namespace App\Models\SIMRS;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatalRegister extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
