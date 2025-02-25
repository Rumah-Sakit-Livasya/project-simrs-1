<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class PendidikanPelatihan extends Model implements Auditable
{
    use SoftDeletes, HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];
    protected $table = 'pendidikan_pelatihan';

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'pendidikan_pelatihan_employee', 'pendidikan_pelatihan_id', 'employee_id');
    }
}
