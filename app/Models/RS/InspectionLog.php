<?php

namespace App\Models\RS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_type',
        'inspection_date',
        'material_approval_id',
        'reference_document',
        'description',
        'result',
        'notes',
        'inspected_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'inspection_date' => 'date',
    ];

    /**
     * Get the user who performed the inspection.
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    /**
     * Get the approved material associated with the inspection (if any).
     */
    public function materialApproval(): BelongsTo
    {
        return $this->belongsTo(MaterialApproval::class, 'material_approval_id');
    }
}
