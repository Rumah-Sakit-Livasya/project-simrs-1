<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyLinenInput extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function picEmployee()
    {
        return $this->belongsTo(Employee::class, 'pic_id');
    }

    public function linenType()
    {
        return $this->belongsTo(LinenType::class, 'linen_type_id');
    }

    public function linenCategory()
    {
        return $this->belongsTo(LinenCategory::class, 'linen_category_id');
    }
}
