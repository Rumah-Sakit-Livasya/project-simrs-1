<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupChartOfAccount extends Model
{
    use SoftDeletes;

    protected $table = 'group_chart_of_account';
    protected $guarded = ['id'];
}
