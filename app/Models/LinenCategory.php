<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinenCategory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function dailyLinenInputs()
    {
        return $this->hasMany(DailyLinenInput::class, 'linen_category_id');
    }
}
