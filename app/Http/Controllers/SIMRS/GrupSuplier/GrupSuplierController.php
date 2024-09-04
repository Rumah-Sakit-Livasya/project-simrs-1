<?php

namespace App\Http\Controllers\SIMRS\GrupSuplier;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GrupSuplier\GrupSuplier;
use Illuminate\Http\Request;

class GrupSuplierController extends Controller
{
    public function index()
    {
        $grup_suplier = GrupSuplier::all();
        return view('pages.simrs.master-data.grup-suplier.index', compact('grup_suplier'));
    }

    public function getGrup($id)
    {
        try {
            $grup_suplier = GrupSuplier::findOrFail($id);

            return response()->json([
                'kategori' => $grup_suplier->kategori,
                'status' => $grup_suplier->status,
                'coa_utang' => $grup_suplier->coa_utang,
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
            'kategori' => 'required',
            'status' => 'required',
        ]);

        try {
            $store = GrupSuplier::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kategori' => 'required',
            'status' => 'required',
        ]);

        try {
            $grup_suplier = GrupSuplier::find($id);
            $grup_suplier->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_suplier = GrupSuplier::find($id);
            $grup_suplier->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
