<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DownPayment extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'down_payment';

    public function bilingan()
    {
        // return $this->belongsTo(Bilingan::class, 'registration_id');
        return $this->belongsTo(Bilingan::class, 'bilingan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isNotEmpty()
    {
        return $this->exists;
    }
}
