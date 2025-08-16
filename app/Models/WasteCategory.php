<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function dailyWasteInputs()
    {
        return $this->hasMany(DailyWasteInput::class);
    }
}
