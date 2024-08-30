<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Persalinan\KategoriPersalinan;
use Illuminate\Http\Request;

class KategoriPersalinanController extends Controller
{
    public function index()
    {
        $kategori_persalinan = KategoriPersalinan::all();
        return view('pages.simrs.master-data.persalinan.kategori.index', compact('kategori_persalinan'));
    }

    public function getKategori($id)
    {
        try {
            $kategori_persalinan = KategoriPersalinan::findOrFail($id);

            return response()->json([
                'nama' => $kategori_persalinan->nama,
                'is_aktif' => $kategori_persalinan->is_aktif,
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
            'nama' => 'required',
            'is_aktif' => 'required',
        ]);

        try {
            $store = KategoriPersalinan::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'is_aktif' => 'required',
        ]);

        try {
            $grup_kategori_persalinan = KategoriPersalinan::find($id);
            $grup_kategori_persalinan->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_kategori_persalinan = KategoriPersalinan::find($id);
            $grup_kategori_persalinan->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
