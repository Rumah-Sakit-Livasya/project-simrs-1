<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\TipeLaboratorium;
use Illuminate\Http\Request;

class TipeLaboratoriumController extends Controller
{
    public function index()
    {
        $tipe_laboratorium = TipeLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.tipe-lab', compact('tipe_laboratorium'));
    }

    public function getTipe($id)
    {
        try {
            $tipe_laboratorium = TipeLaboratorium::findOrFail($id);

            return response()->json([
                'nama_tipe' => $tipe_laboratorium->nama_tipe,
                'status' => $tipe_laboratorium->status,
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_tipe' => 'required',
            'status' => 'required',
        ]);

        try {
            $store = TipeLaboratorium::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_tipe' => 'required',
            'status' => 'required',
        ]);

        try {
            $tipe_laboratorium = TipeLaboratorium::find($id);
            $tipe_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tipe_laboratorium = TipeLaboratorium::find($id);
            $tipe_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
