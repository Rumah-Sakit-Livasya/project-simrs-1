<?php

namespace App\Models\RS;

use App\Models\User;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'material_name',
        'brand',
        'type_or_model',
        'quantity', // <-- TAMBAHKAN INI
        'satuan_id', // <-- TAMBAHKAN INI
        'technical_specifications',
        'image_path',
        'status',
        'submitted_by',
        'reviewed_by',
        'remarks',
    ];

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the document associated with this material approval.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * Get the unit for the material.
     */
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id');
    }
}
