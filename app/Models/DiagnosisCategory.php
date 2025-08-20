<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// app/Models/DiagnosisCategory.php
class DiagnosisCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];

    public function nursingDiagnoses()
    {
        return $this->hasMany(NursingDiagnosis::class, 'category_id');
    }
}
