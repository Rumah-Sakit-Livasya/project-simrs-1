<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RncCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_rnc',
        'nama_rnc',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
