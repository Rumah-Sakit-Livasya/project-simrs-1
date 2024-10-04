<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use OwenIt\Auditing\Auditable;

class Kepustakaan extends Model
{
    use HasFactory, SoftDeletes;
    // use HasFactory, SoftDeletes, Auditable;

    protected $table = 'kepustakaan', $fillable = ['name', 'organization_id', 'kategori', 'type', 'parent_id', 'size', 'file'];

    // Relasi ke parent
    public function parent()
    {
        return $this->belongsTo(Kepustakaan::class, 'parent_id');
    }

    // Relasi ke child
    public function children()
    {
        return $this->hasMany(Kepustakaan::class, 'parent_id');
    }
}
