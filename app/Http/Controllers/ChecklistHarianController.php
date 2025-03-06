<?php

namespace App\Http\Controllers;

use App\Models\ChecklistHarian;
use App\Models\ChecklistHarianCategory;
use Illuminate\Http\Request;

class ChecklistHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checklistHarian = checklistHarian::orderBy('created_at', 'desc')->get();
        $checklistKategori = ChecklistHarianCategory::orderBy('created_at', 'desc')->get();
        return view('pages.checklist-harian.admin.index', compact('checklistHarian', 'checklistKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kegiatan' => 'max:255|required',
            'checklist_harian_category_id' => 'max:255|required',
        ]);

        try {
            $store = ChecklistHarian::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChecklistHarian $checklistHarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChecklistHarian $checklistHarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kegiatan' => 'max:255|required',
            'checklist_harian_category' => 'max:255',
            // Validasi untuk is_active tidak diperlukan di sini
        ]);

        try {
            $checklist = ChecklistHarian::findOrFail($id);
            $checklist->update($validatedData);
            return response()->json(['message' => "$checklist->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistHarian $checklistHarian)
    {
        //
    }

    public function getChecklist($id)
    {
        try {
            $checklist = ChecklistHarian::findOrFail($id);

            return response()->json([
                'kegiatan' => $checklist->kegiatan,
                'checklist_harian_category' => $checklist->checklist_harian_category,
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
}
