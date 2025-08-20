<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// app/Models/NursingDiagnosis.php
class NursingDiagnosis extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['category_id', 'code', 'diagnosa'];

    public function category()
    {
        return $this->belongsTo(DiagnosisCategory::class, 'category_id');
    }
}
