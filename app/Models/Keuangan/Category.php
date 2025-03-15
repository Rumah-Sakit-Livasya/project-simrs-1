<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function tansaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
