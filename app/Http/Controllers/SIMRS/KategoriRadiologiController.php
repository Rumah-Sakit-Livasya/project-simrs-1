<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\KategoriRadiologi;
use Illuminate\Http\Request;

class KategoriRadiologiController extends Controller
{
    public function index()
    {
        $kategori_radiologi = KategoriRadiologi::all();
        return view('pages.simrs.master-data.penunjang-medis.radiologi.kategori-radiologi', compact('kategori_radiologi'));
    }

    public function getGrupParameter($id)
    {
        try {
            $kategori_radiologi = KategoriRadiologi::findOrFail($id);

            return response()->json([
                'nama_kategori' => $kategori_radiologi->nama_kategori,
                'status' => $kategori_radiologi->status,
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
            $store = KategoriRadiologi::create($validatedData);
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
            $kategori_radiologi = KategoriRadiologi::find($id);
            $kategori_radiologi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $kategori_radiologi = KategoriRadiologi::find($id);
            $kategori_radiologi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
