<?php

namespace App\Http\Controllers\SIMRS\Peralatan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Peralatan\Peralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        $peralatan = Peralatan::all();
        return view('pages.simrs.master-data.peralatan.index', compact('peralatan'));
    }

    public function getPeralatan($id)
    {
        try {
            $peralatan = Peralatan::findOrFail($id);

            return response()->json([
                'kode' => $peralatan->kode,
                'nama' => $peralatan->nama,
                'satuan_pakai' => $peralatan->satuan_pakai,
                'is_req_dokter' => $peralatan->is_req_dokter,
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
            'kode' => 'required',
            'nama' => 'required',
            'satuan_pakai' => 'required',
            'is_req_dokter' => 'required',
        ]);


        try {
            $store = Peralatan::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'satuan_pakai' => 'required',
            'is_req_dokter' => 'required',
        ]);

        try {
            $peralatan = Peralatan::find($id);
            $peralatan->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $peralatan = Peralatan::find($id);
            $peralatan->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
