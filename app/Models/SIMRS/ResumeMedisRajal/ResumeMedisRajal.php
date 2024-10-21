<?php

namespace App\Models\SIMRS\ResumeMedisRajal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class ResumeMedisRajal extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;
    protected $table = 'resume_medis_rajal', $guarded = ['id'];
}
