<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinenType extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function dailyLinenInputs()
    {
        return $this->hasMany(DailyLinenInput::class, 'linen_type_id');
    }
}
