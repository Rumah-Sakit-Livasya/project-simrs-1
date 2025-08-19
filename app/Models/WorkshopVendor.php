<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workshop_vendors';
    protected $guarded = ['id'];
}
