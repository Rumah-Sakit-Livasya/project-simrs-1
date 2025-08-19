<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleService extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function internal_vehicle()
    {
        return $this->belongsTo(InternalVehicle::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id')->withDefault()->setTable('vendor_bengkel_spesifikasi');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by_id');
    }

    public function inspectionResult()
    {
        return $this->belongsTo(InspectionResult::class);
    }
}
