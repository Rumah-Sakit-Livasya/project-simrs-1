<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TimeScheduleEmployee extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];
    protected $table = 'time_schedule_employees';

    public function time_shedules()
    {
        return $this->belongsTo(TimeSchedule::class);
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
}
