<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'waste_category_id',
        'vehicle_id',
        'volume',
        'pic',
    ];

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
