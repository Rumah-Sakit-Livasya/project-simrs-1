<?php

namespace App\Models\SIMRS\CPPT;

<<<<<<< HEAD
use App\Models\SIMRS\Registration;
=======
use App\Models\Signature;
use App\Models\SIMRS\Registration;
use App\Models\User;
>>>>>>> 841717927d57ff76a595e6f030bf800256003f35
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class CPPT extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'cppt', $guarded = ['id'];

<<<<<<< HEAD
    public function registration(){
        return $this->belongsTo(Registration::class);
    }
=======
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
>>>>>>> 841717927d57ff76a595e6f030bf800256003f35
}
