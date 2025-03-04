<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class ChecklistHarianCategory extends Model implements Auditable
{
    use SoftDeletes, HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'checklist_harian_categories';
    protected $guarded = ['id'];

    public function checklist_harian()
    {
        return $this->hasMany(ChecklistHarian::class);
    }
}
