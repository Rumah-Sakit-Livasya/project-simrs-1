<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['attendance_code_id', 'clock_in', 'clock_out', 'date', 'employee_id', 'late_clock_in', 'early_clock_out', 'location', 'shift_id', 'day_off_request_id', 'is_day_off', 'foto'];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
    public function attendance_code()
    {
        return $this->belongsTo(AttendanceCode::class, 'attendance_code_id');
    }
    public function day_off()
    {
        return $this->belongsTo(DayOffRequest::class, 'day_off_request_id');
    }
}
