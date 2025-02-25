<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TimeSchedule extends Model implements Auditable
{
    use SoftDeletes, HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];
    protected $table = 'time_schedules';

    public function employee()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'time_schedule_employees', 'time_schedule_id', 'employee_id');
    }
}
