<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the employee associated with the uploaded file.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pic');
    }
}
