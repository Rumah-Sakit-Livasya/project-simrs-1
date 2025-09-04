<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedDocument extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
}
