<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $guarded = ['id'];
    protected $table = 'stock_transactions';
}
