<?php

namespace App\Models\SIMRS;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Kepustakaan extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'kepustakaan';
    protected $fillable = ['name', 'organization_id', 'kategori', 'month', 'year', 'type', 'parent_id', 'size', 'file'];

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

    public function allChildren()
    {
        return $this->children()->with('allChildren')->where('type', 'folder');
    }

    // Relasi ke organization
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
