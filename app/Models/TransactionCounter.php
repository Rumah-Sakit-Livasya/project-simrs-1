<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCounter extends Model
{
    protected $fillable = [
        'prefix',
        'last_number',
    ];
}
