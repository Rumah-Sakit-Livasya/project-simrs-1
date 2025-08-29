<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NursingActivityChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nursing_activity_checklists';

    protected $fillable = [
        'registration_id',
        'user_id',
        'checklist_data',
    ];

    // Ini sangat penting! Otomatis mengubah JSON dari/ke array
    protected $casts = [
        'checklist_data' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class);
    }
}
