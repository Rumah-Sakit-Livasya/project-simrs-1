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
    // app/Models/Organization.php

    // app/Models/Organization.php

    // app/Models/Organization.php

    public static function getAllOrderedByHierarchy(): Collection
    {
        // Bagian ini tidak perlu diubah
        $newRootNames = [
            'Pelayanan Medis',
            'Penunjang Medis',
            'Sub Bagian Keuangan',
            'Sub Bagian Umum',
            'Sub Bagian SDM, Kesekretariatan, Humas & Marketing',
        ];

        $newRootOrganizations = self::whereIn('name', $newRootNames)
            ->orderByRaw("FIELD(name, '" . implode("','", $newRootNames) . "')")
            ->get();

        // --- PERUBAHAN UTAMA 1: Definisikan Peta Nama Tampilan ---
        // Di sini kita definisikan nama apa yang akan diubah menjadi apa.
        $displayNameMap = [
            'Pelayanan Medis' => 'Kepala Seksi Pelayanan Medis',
            'Penunjang Medis' => 'Kepala Seksi Penunjang Medis',
            'Sub Bagian Keuangan' => 'Kepala Sub Bagian Keuangan',
            'Sub Bagian Umum' => 'Kepala Sub Bagian Umum & Rumah Tangga',
            'Sub Bagian SDM, Kesekretariatan, Humas & Marketing' => 'Kepala Sub Bagian SDM, Kesekretariatan, Humas & Marketing',
            // Anda bisa menambahkan 'terjemahan' lain di sini jika perlu
        ];

        $sortedList = [];
        $processedIds = [];

        // Pastikan $displayNameMap dimasukkan ke dalam scope fungsi traverse
        $traverse = function (Organization $organization, int $depth) use (&$sortedList, &$traverse, &$processedIds, $displayNameMap) {
            if (in_array($organization->id, $processedIds)) {
                return;
            }
            $processedIds[] = $organization->id;

            // --- PERUBAHAN UTAMA 2: Gunakan Peta Nama ---
            $originalName = $organization->name;
            // Cek apakah nama asli ada di dalam peta. Jika ada, gunakan nama baru.
            // Jika tidak, gunakan nama asli (fallback).
            $displayName = $displayNameMap[$originalName] ?? $originalName;

            $sortedList[] = [
                'id'            => $organization->id,
                'name'          => $displayName, // Gunakan nama tampilan baru
                'depth'         => $depth,
                'prefixed_name' => str_repeat('— ', $depth) . $displayName, // Gunakan nama tampilan baru juga di sini
            ];

            // Bagian ini tidak perlu diubah
            $childrenStructures = $organization->child_structures()->with('organization')->get()
                ->sortBy(function ($structure) {
                    return $structure->organization ? $structure->organization->name : '';
                });

            foreach ($childrenStructures as $childStructure) {
                if ($childStructure->organization) {
                    $traverse($childStructure->organization, $depth + 1);
                }
            }
        };

        // Bagian ini tidak perlu diubah
        foreach ($newRootOrganizations as $root) {
            $traverse($root, 0);
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
