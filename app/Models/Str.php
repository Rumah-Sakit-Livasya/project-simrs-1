<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Str extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'strs';
    protected $fillable = ['employee_id', 'str_number', 'str_expiry_date', 'is_lifetime'];
    protected $casts = [
        'is_lifetime' => 'boolean',
        'str_expiry_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
