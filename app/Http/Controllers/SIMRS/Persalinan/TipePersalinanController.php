<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Persalinan\TipePersalinan;
use Illuminate\Http\Request;

class TipePersalinanController extends Controller
{
    public function index()
    {
        $tipe_persalinan = TipePersalinan::all();
        return view('pages.simrs.master-data.persalinan.tipe.index', compact('tipe_persalinan'));
    }

    public function getKategori($id)
    {
        try {
            $tipe_persalinan = TipePersalinan::findOrFail($id);

            return response()->json([
                'tipe' => $tipe_persalinan->tipe,
                'persentase' => $tipe_persalinan->persentase,
                'operator' => $tipe_persalinan->operator,
                'anestesi' => $tipe_persalinan->anestesi,
                'prediatric' => $tipe_persalinan->prediatric,
                'room' => $tipe_persalinan->room,
                'observasi' => $tipe_persalinan->observasi,
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
            'tipe' => 'required',
            'persentase' => 'required',
            'operator' => 'required',
            'anestesi' => 'required',
            'prediatric' => 'required',
            'room' => 'required',
            'observasi' => 'required',
        ]);

        try {
            $store = TipePersalinan::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tipe' => 'required',
            'persentase' => 'required',
            'operator' => 'required',
            'anestesi' => 'required',
            'prediatric' => 'required',
            'room' => 'required',
            'observasi' => 'required',
        ]);

        try {
            $grup_tipe_persalinan = TipePersalinan::find($id);
            $grup_tipe_persalinan->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_tipe_persalinan = TipePersalinan::find($id);
            $grup_tipe_persalinan->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
