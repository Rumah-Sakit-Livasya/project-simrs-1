<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// app/Models/Intervention.php
class Intervention extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'tipe_rawat'];
}
