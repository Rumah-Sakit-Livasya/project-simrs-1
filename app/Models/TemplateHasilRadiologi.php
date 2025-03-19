<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateHasilRadiologi extends Model
{
    protected $table = 'template_hasil_radiologi';

    use SoftDeletes;

    protected $guarded = [
        'id'
    ];
}
