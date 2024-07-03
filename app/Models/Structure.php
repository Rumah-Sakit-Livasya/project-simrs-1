<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;
    protected $fillable = ['child_organization', 'parent_organization'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'child_organization');
    }
    public function organization_parent()
    {
        return $this->belongsTo(Organization::class, 'parent_organization');
    }
}
