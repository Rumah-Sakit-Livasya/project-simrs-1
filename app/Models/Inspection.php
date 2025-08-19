<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
    public function results()
    {
        return $this->hasMany(InspectionResult::class);
    }
}
