<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLaboratorium extends Model
{
    protected $table = 'order_laboratorium';

    protected $guarded =[
        'id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];    
}
