<?php

namespace App\Models\SIMRS\ResumeMedisRajal;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class ResumeMedisRajal extends Model implements Auditable
{
    use HasFactory, AuditingAuditable, SoftDeletes;
    protected $table = 'resume_medis_rajal', $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
