<?php

namespace App\Models;

use App\Models\Inventaris\RoomMaintenance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function child_structures()
    {
        return $this->hasMany(Structure::class, 'parent_organization');
    }
    public function parent_structures()
    {
        return $this->hasMany(Structure::class, 'child_organization');
    }
    public function user()
    {
        return $this->hasMany(Employee::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function room_maintenance()
    {
        return $this->belongsToMany(RoomMaintenance::class, 'room_maintenance_organization');
    }
}
