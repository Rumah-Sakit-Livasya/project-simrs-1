<?php

namespace App\Models\SIMRS\Dokter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoapTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_name',
        'subjective',
        'objective',
        'assesment',
        'planning',
    ];
}
