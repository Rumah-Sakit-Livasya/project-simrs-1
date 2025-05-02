<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanInternal extends Model
{
    use SoftDeletes;
    protected $table = 'laporan_internal';
    protected $guarded = ['id'];
}
