<?php

namespace App\Http\Controllers;

use App\Models\ChecklistHarianCategory;
use Illuminate\Http\Request;

class ChecklistHarianCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checklistHarianCategory = checklistHarianCategory::orderBy('created_at', 'desc')->get();
        return view('pages.checklist-harian.category.index', compact('checklistHarianCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
        ]);

        try {
            $store = ChecklistHarianCategory::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCategory($id)
    {
        try {
            $room = ChecklistHarianCategory::findOrFail($id);

            return response()->json([
                'name' => $room->name,
                'is_active' => $room->is_active,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            // Validasi untuk is_active tidak diperlukan di sini
        ]);

        // Cek apakah 'is_active' ada dalam request, jika tidak, set ke 0
        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            $room = ChecklistHarianCategory::findOrFail($id);
            $room->update($validatedData);
            return response()->json(['message' => "$room->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $room = ChecklistHarianCategory::find($id);
            $room->delete();
            return response()->json(['message' => "$room->name Berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
