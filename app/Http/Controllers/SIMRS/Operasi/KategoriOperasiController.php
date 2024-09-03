<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use Illuminate\Http\Request;

class KategoriOperasiController extends Controller
{
    public function index()
    {
        $kategori_operasi = KategoriOperasi::all();
        return view('pages.simrs.master-data.operasi.kategori.index', compact('kategori_operasi'));
    }

    public function getKategori($id)
    {
        try {
            $kategori_operasi = KategoriOperasi::findOrFail($id);

            return response()->json([
                'nama_kategori' => $kategori_operasi->nama_kategori,
                'urutan' => $kategori_operasi->urutan,
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
            'urutan' => 'required',
        ]);

        try {
            $store = KategoriOperasi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required',
            'urutan' => 'required',
        ]);

        try {
            $kategori_operasi = KategoriOperasi::find($id);
            $kategori_operasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $kategori_operasi = KategoriOperasi::find($id);
            $kategori_operasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
