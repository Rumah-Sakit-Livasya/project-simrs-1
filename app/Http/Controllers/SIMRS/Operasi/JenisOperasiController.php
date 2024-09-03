<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Operasi\JenisOperasi;
use Illuminate\Http\Request;

class JenisOperasiController extends Controller
{
    public function index()
    {
        $jenis_operasi = JenisOperasi::all();
        return view('pages.simrs.master-data.operasi.jenis.index', compact('jenis_operasi'));
    }

    public function getJenis($id)
    {
        try {
            $jenis_operasi = JenisOperasi::findOrFail($id);

            return response()->json([
                'jenis' => $jenis_operasi->jenis,
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
            'jenis' => 'required',
        ]);

        try {
            $store = JenisOperasi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jenis' => 'required',
        ]);

        try {
            $jenis_operasi = JenisOperasi::find($id);
            $jenis_operasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $jenis_operasi = JenisOperasi::find($id);
            $jenis_operasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
