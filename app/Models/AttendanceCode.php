<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCode extends Model
{
    use HasFactory;
    protected $table = 'attendance_codes';
    protected $fillable = ['code', 'description', 'total_hari'];

    public function day_off_request()
    {
        $this->hasOne(DayOffRequest::class);
    }
    public function attendance()
    {
        $this->hasOne(Attendance::class);
    }
}
