<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Structure;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // Ambil parent tertinggi (organisasi yang tidak menjadi child)
        $organizations = Organization::with([
            'employees' => function ($q) {
                $q->where('is_active', 1)
                    ->whereNull('resign_date')
                    ->whereNotNull('organization_id');
            },
            'employees.jobLevel',
            'employees.jobPosition',
            'child_structures.organization' // Ambil anak-anak organisasi
        ])
            ->whereDoesntHave('parent_structures') // Ambil organisasi yang tidak menjadi child
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama
            ->get();

        // Array untuk melacak ID organisasi yang sudah diproses
        $processedIds = [];

        // Bangun tree organisasi
        foreach ($organizations as $organization) {
            // Cek apakah organisasi sudah diproses
            if (in_array($organization->id, $processedIds)) {
                continue; // Lewati jika sudah diproses
            }

            // Tandai organisasi sebagai sudah diproses
            $processedIds[] = $organization->id;

            // Bangun tree untuk anak-anak organisasi
            $organization->child_nodes = $this->buildOrganizationTree($organization->id, $processedIds);
        }

        return view('pages.pegawai.team.index', compact('organizations'));
    }

    public function search(Request $request)
    {
        $query = trim($request->q);

        if ($query === '') {
            // Jika query kosong, tampilkan semua data seperti default
            $organizations = Organization::with([
                'employees' => function ($q) {
                    $q->where('is_active', 1)
                        ->whereNull('resign_date')
                        ->whereNotNull('organization_id');
                },
                'employees.jobLevel',
                'employees.jobPosition',
                'child_structures.organization' // Ambil anak-anak organisasi
            ])
                ->whereDoesntHave('parent_structures') // Ambil organisasi yang tidak menjadi child
                ->orderBy('name', 'asc') // Urutkan berdasarkan nama
                ->get();

            // Array untuk melacak ID organisasi yang sudah diproses
            $processedIds = [];

            // Bangun tree organisasi
            foreach ($organizations as $organization) {
                if (in_array($organization->id, $processedIds)) {
                    continue; // Lewati jika sudah diproses
                }

                $processedIds[] = $organization->id;
                $organization->child_nodes = $this->buildOrganizationTree($organization->id, $processedIds);
            }
        } else {
            // Jika ada pencarian
            $organizations = Organization::where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhereHas('employees', function ($q) use ($query) {
                        $q->where('is_active', 1)
                            ->whereNull('resign_date')
                            ->whereNotNull('organization_id')
                            ->where(function ($q) use ($query) {
                                $q->where('fullname', 'like', '%' . $query . '%')
                                    ->orWhere('email', 'like', '%' . $query . '%')
                                    ->orWhere('mobile_phone', 'like', '%' . $query . '%');
                            });
                    });
            })
                ->with([
                    'employees' => function ($q) {
                        $q->where('is_active', 1)
                            ->whereNull('resign_date')
                            ->whereNotNull('organization_id');
                    },
                    'employees.jobLevel',
                    'employees.jobPosition',
                    'child_structures.organization' // Ambil anak-anak organisasi
                ])
                ->orderBy('name', 'asc') // Urutkan berdasarkan nama
                ->get();

            // Array untuk melacak ID organisasi yang sudah diproses
            $processedIds = [];

            // Bangun tree organisasi
            foreach ($organizations as $organization) {
                if (in_array($organization->id, $processedIds)) {
                    continue; // Lewati jika sudah diproses
                }

                $processedIds[] = $organization->id;
                $organization->child_nodes = $this->buildOrganizationTree($organization->id, $processedIds);
            }
        }

        $html = view('pages.pegawai.team.partials.employee-cards', [
            'organizations' => $organizations
        ])->render();

        return response()->json(['html' => $html]);
    }

    private function buildOrganizationTree($parentId = null, &$processedIds = [])
    {
        // Ambil struktur organisasi berdasarkan parent ID
        $structures = Structure::with(['organization.employees' => function ($q) {
            $q->where('is_active', 1)
                ->whereNull('resign_date')
                ->whereNotNull('organization_id');
        }])
            ->where('parent_organization', $parentId) // Ambil child berdasarkan parent
            ->orderBy('id', 'asc') // Urutkan berdasarkan ID organisasi
            ->get();

        $tree = [];

        foreach ($structures as $structure) {
            $org = $structure->organization;

            // Cek apakah organisasi sudah diproses
            if (in_array($org->id, $processedIds)) {
                continue; // Lewati jika sudah diproses
            }

            // Tandai organisasi sebagai sudah diproses
            $processedIds[] = $org->id;

            // Rekursif untuk anak-anak organisasi
            $org->child_nodes = $this->buildOrganizationTree($org->id, $processedIds);

            $tree[] = $org;
        }

        return $tree;
    }
}
