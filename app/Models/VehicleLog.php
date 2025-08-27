<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function internal_vehicle()
    {
        return $this->belongsTo(InternalVehicle::class, 'internal_vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
