<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use Illuminate\Http\Request;

class KategoriLaboratorumController extends Controller
{
    public function index()
    {
        $kategori_laboratorium = KategoriLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.kategori-lab', compact('kategori_laboratorium'));
    }

    public function getKategori($id)
    {
        try {
            $kategori_laboratorium = KategoriLaboratorium::findOrFail($id);

            return response()->json([
                'nama_kategori' => $kategori_laboratorium->nama_kategori,
                'status' => $kategori_laboratorium->status,
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
            'nama_kategori' => 'required',
            'status' => 'required',
        ]);

        try {
            $store = KategoriLaboratorium::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required',
            'status' => 'required',
        ]);

        try {
            $kategori_laboratorium = KategoriLaboratorium::find($id);
            $kategori_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $kategori_laboratorium = KategoriLaboratorium::find($id);
            $kategori_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
