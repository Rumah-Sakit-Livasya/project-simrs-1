<?php

namespace App\Models\SIMRS;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatalRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'batal_register';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
