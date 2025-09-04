<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InpatientInitialExamination extends Model
{
    use HasFactory;

    protected $table = 'inpatient_initial_examinations';
    protected $guarded = ['id'];

    protected $casts = [
        'allergy_medicine' => 'array',
        'allergy_food' => 'array',
    ];
}
