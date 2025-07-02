<?php

namespace App\Models\Keuangan;

use App\Models\Keuangan\Pengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pencairan extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu Pencairan dimiliki oleh satu Pengajuan
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function userEntry(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }
    // app/Models/Pencairan.php (atau di mana pun lokasinya)
    // ...
    // Di model Pencairan.php
    public function pertanggungjawaban()
    {
        return $this->hasMany(Pertanggungjawaban::class);
    }


    public function getTotalTelahDipertanggungjawabkanAttribute()
    {
        return $this->pertanggungjawaban->sum('total_pj');
    }

    public function getSisaYangBelumDipertanggungjawabkanAttribute()
    {
        return $this->nominal_pencairan - $this->total_telah_dipertanggungjawabkan;
    }
}
