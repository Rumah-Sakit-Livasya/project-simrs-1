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

    public static function getAllOrderedByHierarchy(): Collection
    {
        // --- PERUBAHAN UTAMA DIMULAI DI SINI ---

        // 1. Definisikan nama-nama unit yang ingin Anda jadikan level teratas (akar baru).
        // Urutan di dalam array ini akan menentukan urutan di laporan.
        $newRootNames = [
            'Pelayanan Medis',
            'Penunjang Medis',
            'Sub Bagian Keuangan',
            'Sub Bagian Umum',
            'Sub Bagian SDM, Kesekretariatan, Humas & Marketing', // Tambahkan yang lain jika perlu
            'SOD' // Tambahkan yang lain jika perlu
        ];

        // 2. Ambil objek Organization berdasarkan nama-nama di atas.
        // Gunakan `whereIn` untuk efisiensi dan `orderByRaw(FIELD(...))` untuk menjaga urutan.
        $newRootOrganizations = self::whereIn('name', $newRootNames)
            ->orderByRaw("FIELD(name, '" . implode("','", $newRootNames) . "')")
            ->get();

        $sortedList = [];
        $processedIds = [];

        // Fungsi rekursif (traverse) tidak perlu diubah, ia akan bekerja dengan akar baru
        $traverse = function (Organization $organization, int $depth) use (&$sortedList, &$traverse, &$processedIds) {
            if (in_array($organization->id, $processedIds)) {
                return;
            }
            $processedIds[] = $organization->id;

            $sortedList[] = [
                'id'            => $organization->id,
                'name'          => $organization->name,
                'depth'         => $depth,
                'prefixed_name' => str_repeat('— ', $depth) . $organization->name,
            ];

            // Urutkan anak-anak berdasarkan nama secara default
            $childrenStructures = $organization->child_structures()->with('organization')->get()
                ->sortBy(function ($structure) {
                    // Pastikan ada organization sebelum mencoba mengakses nama
                    return $structure->organization ? $structure->organization->name : '';
                });

            foreach ($childrenStructures as $childStructure) {
                // Pastikan ada organization sebelum melakukan rekursi
                if ($childStructure->organization) {
                    $traverse($childStructure->organization, $depth + 1);
                }
            }
        };

        // 3. Mulai penelusuran dari setiap "akar baru" yang sudah kita definisikan dan urutkan.
        foreach ($newRootOrganizations as $root) {
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
