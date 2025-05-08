<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamMakanGizi extends Model
{
    use SoftDeletes;
    protected $table = "jam_makan_gizi";
    protected $guarded = ["id"];
}
