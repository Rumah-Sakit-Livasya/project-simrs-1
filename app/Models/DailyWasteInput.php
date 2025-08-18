<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyWasteInput extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['date', 'waste_category_id', 'volume', 'pic'];

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'pic');
    }
}
