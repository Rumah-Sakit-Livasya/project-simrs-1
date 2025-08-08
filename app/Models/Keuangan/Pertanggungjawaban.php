<?php

// app/Models/Keuangan/Pertanggungjawaban.php
namespace App\Models\Keuangan;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pertanggungjawaban extends Model
{
    protected $guarded = ['id'];

    public function pencairan()
    {
        return $this->belongsTo(Pencairan::class);
    }
    public function details()
    {
        return $this->hasMany(PertanggungjawabanDetail::class);
    }
    public function userEntry()
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }
}
