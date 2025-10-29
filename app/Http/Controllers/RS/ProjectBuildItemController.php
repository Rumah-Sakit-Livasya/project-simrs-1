<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\ProjectBuildItem;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class ProjectBuildItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectBuildItem::with(['kategori', 'satuan'])->select('project_build_items.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non-Aktif</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-primary btn-sm mr-1 edit-btn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Hapus</button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        $kategoris = WarehouseKategoriBarang::all();
        $satuans = WarehouseSatuanBarang::all();
        return view('app-type.rs.project-build-items.index', compact('kategoris', 'satuans'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_code' => 'required|string|unique:project_build_items,item_code',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'is_active' => 'required|boolean',
        ]);
        ProjectBuildItem::create($validated);
        return response()->json(['success' => 'Master Item Proyek berhasil ditambahkan.']);
    }

    public function edit(ProjectBuildItem $projectBuildItem): JsonResponse
    {
        return response()->json($projectBuildItem);
    }

    public function update(Request $request, ProjectBuildItem $projectBuildItem): JsonResponse
    {
        $validated = $request->validate([
            'item_code' => 'required|string|unique:project_build_items,item_code,' . $projectBuildItem->id,
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'is_active' => 'required|boolean',
        ]);
        $projectBuildItem->update($validated);
        return response()->json(['success' => 'Master Item Proyek berhasil diperbarui.']);
    }

    public function destroy(ProjectBuildItem $projectBuildItem): JsonResponse
    {
        if ($projectBuildItem->materialApprovals()->exists()) {
            return response()->json(['error' => 'Tidak bisa dihapus! Item ini sudah digunakan di Persetujuan Material.'], 422);
        }
        $projectBuildItem->delete();
        return response()->json(['success' => 'Master Item Proyek berhasil dihapus.']);
    }
}
