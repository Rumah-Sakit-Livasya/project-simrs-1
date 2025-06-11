<?php

namespace App\Models\SIMRS\CPPT;

use App\Models\Signature;
use App\Models\SIMRS\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class CPPT extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'cppt', $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function signature()
    {
        return $this->morphOne(Signature::class, 'signable');
    }
}
