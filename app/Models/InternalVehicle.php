<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InternalVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'internal_vehicles';

    public function inspectionResults()
    {
        return $this->hasMany(InspectionResult::class);
    }

    public function vehicle_services()
    {
        return $this->hasMany(VehicleService::class);
    }

    public function vehicleLogs()
    {
        return $this->hasMany(VehicleLog::class, 'internal_vehicle_id');
    }

    // --- AKSESOR BARU UNTUK MENGHITUNG TOTAL BIAYA ---
    // Laravel akan secara otomatis membuat properti virtual bernama 'total_maintenance_cost'
    public function getTotalMaintenanceCostAttribute()
    {
        // Pastikan relasi 'services' sudah di-load (eager loaded) untuk menghindari N+1 query
        if (! $this->relationLoaded('services')) {
            return 0; // Atau $this->services->... jika Anda ingin lazy load (tidak disarankan di loop)
        }

        // Jumlahkan 'labor_cost' dan 'parts_cost' dari semua service yang terkait
        return $this->services->sum(function ($service) {
            return $service->labor_cost + $service->parts_cost;
        });
    }
}
