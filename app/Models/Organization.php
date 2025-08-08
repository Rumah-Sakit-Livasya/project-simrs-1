<?php

namespace App\Models;

use App\Models\Inventaris\RoomMaintenance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    /**
     * Mengambil semua organisasi dan mengurutkannya berdasarkan hierarki.
     * Menghasilkan Collection yang berisi array asosiatif.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllOrderedByHierarchy(): Collection
    {
        // 1. Temukan semua organisasi yang tidak memiliki induk (akar/root)
        // Organisasi yang tidak pernah menjadi 'child_organization' di tabel Structure
        $rootOrganizations = self::whereDoesntHave('parent_structures')->get();

        $sortedList = [];

        // 2. Buat fungsi rekursif untuk menelusuri anak-anak
        $traverse = function (Organization $organization, int $depth) use (&$sortedList, &$traverse) {
            // Tambahkan organisasi saat ini ke dalam daftar
            $sortedList[] = [
                'id'            => $organization->id,
                'name'          => $organization->name,
                'depth'         => $depth,
                'prefixed_name' => str_repeat('— ', $depth) . $organization->name,
            ];

            // Telusuri setiap anak dari organisasi saat ini
            // Kita perlu me-load relasi 'organization' untuk menghindari N+1 query problem
            foreach ($organization->child_structures()->with('organization')->get() as $childStructure) {
                // Panggil kembali fungsi ini untuk anak, dengan menambah level kedalaman
                $traverse($childStructure->organization, $depth + 1);
            }
        };

        // 3. Mulai penelusuran dari setiap organisasi akar
        foreach ($rootOrganizations as $root) {
            $traverse($root, 0); // Mulai dari level 0
        }

        return collect($sortedList);
    }

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
