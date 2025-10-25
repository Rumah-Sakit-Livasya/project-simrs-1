<?php

namespace App\Models\RS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'title',
        'description',
        'document_type_id',
        'status',
        'file_path',
        'file_name',
        'file_size',
        'uploader_id',
        'person_in_charge_id',
        'parent_id',
        'version',
        'is_latest',
    ];

    /**
     * Relasi ke Tipe Dokumen.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Relasi ke user yang mengupload dokumen.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Relasi ke user penanggung jawab.
     */
    public function personInCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'person_in_charge_id');
    }

    /**
     * Relasi ke dokumen induk (versi sebelumnya).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    /**
     * Relasi ke dokumen turunan (versi-versi baru).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_id');
    }
}
