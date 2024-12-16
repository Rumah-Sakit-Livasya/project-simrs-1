<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequestLamp extends Model
{
    protected $table = 'attendance_request_lamp', $fillable = ['tanggal', 'organization_id', 'lampiran'];

    public function attendance_request_lamp_details()
    {
        return $this->hasMany(AttendanceRequestLampDetail::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
