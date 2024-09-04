<?php

namespace App\Models\SIMRS\GrupSuplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupSuplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grup_suplier', $fillable = ['kategori', 'coa_utang', 'status'];
}
