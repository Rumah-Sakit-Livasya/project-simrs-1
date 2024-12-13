<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequestLamp extends Model
{
    protected $table = 'attendance_request_lamp', $fillable = ['tanggal', 'lampiran'];
}
