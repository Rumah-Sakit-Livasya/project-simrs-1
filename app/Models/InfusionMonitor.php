<?php

// app/Models/InfusionMonitor.php
namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class InfusionMonitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'infusion_monitors';

    protected $fillable = [
        'registration_id',
        'waktu_infus',
        'kolf_ke',
        'jenis_cairan',
        'keterangan',
        'cairan_masuk',
        'cairan_sisa',
        'nama_perawat',
        'user_id',
    ];

    // Cast 'waktu_infus' ke objek Carbon untuk kemudahan formatting
    protected $casts = [
        'waktu_infus' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
