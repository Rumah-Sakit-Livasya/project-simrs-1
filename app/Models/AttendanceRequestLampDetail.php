<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequestLampDetail extends Model
{
    protected $guarded = ['id'], $table = 'attendance_request_lamp_detail';

    public function attendance_request_lamp()
    {
        return $this->belongsTo(AttendanceRequestLamp::class, 'attendance_request_lamp_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
