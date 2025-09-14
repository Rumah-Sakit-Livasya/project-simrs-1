<?php

namespace App\Models\BPJS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SepApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sep_approvals';

    protected $fillable = [
        'nokartu',
        'jns_pelayanan',
        'jnspengajuan',
        'tglsep',
        'keterangan',
        'status',
    ];
}
