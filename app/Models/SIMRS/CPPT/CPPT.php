<?php

namespace App\Models\SIMRS\CPPT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class CPPT extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'cppt', $guarded = ['id'];
}
