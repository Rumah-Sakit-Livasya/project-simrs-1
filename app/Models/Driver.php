<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    /**
     * Setiap Driver adalah seorang Employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function vehicleLogs()
    {
        return $this->hasMany(VehicleLog::class);
    }
}
