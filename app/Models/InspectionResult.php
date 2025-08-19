<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionResult extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(InternalVehicle::class, 'internal_vehicle_id');
    }
    public function item()
    {
        return $this->belongsTo(InspectionItem::class, 'inspection_item_id');
    }
}
