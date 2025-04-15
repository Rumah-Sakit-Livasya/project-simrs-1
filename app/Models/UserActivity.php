<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = ['user_id', 'action', 'model_type', 'model_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
