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

    public function getAllChildAssociative()
    {
        return $this->child_structures
            ->flatMap(fn($child) => array_merge(
                [['id' => $child->organization->id, 'name' => $child->organization->name]],
                $child->organization->getAllChildAssociative()
            ))
            ->toArray();
    }

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
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function laporan_internal()
    {
        return $this->hasMany(LaporanInternal::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function room_maintenance()
    {
        return $this->belongsToMany(RoomMaintenance::class, 'room_maintenance_organization');
    }

    public function checklist_harian()
    {
        return $this->hasMany(ChecklistHarian::class);
    }
}
