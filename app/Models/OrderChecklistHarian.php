<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderChecklistHarian extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'order_checklist_harian';

    public function checklist_harian()
    {
        return $this->belongsTo(ChecklistHarian::class);
    }
}
