<?php

namespace App\Models\SIMRS\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaMaterai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biaya_materai', $fillable = ['biaya_materai', 'min_tarif', 'max_tarif'];
}
