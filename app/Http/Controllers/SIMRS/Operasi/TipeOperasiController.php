<?php

namespace App\Http\Controllers\SIMRS\Operasi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Operasi\TipeOperasi;
use Illuminate\Http\Request;

class TipeOperasiController extends Controller
{
    public function index()
    {
        $tipe_operasi = TipeOperasi::all();
        return view('pages.simrs.master-data.operasi.tipe.index', compact('tipe_operasi'));
    }

    public function getTipe($id)
    {
        try {
            $tipe_operasi = TipeOperasi::findOrFail($id);

            return response()->json([
                'tipe' => $tipe_operasi->tipe,
                'operator' => $tipe_operasi->operator,
                'anestesi' => $tipe_operasi->anestesi,
                'resusitator' => $tipe_operasi->resusitator,
                'dokter_tambahan' => $tipe_operasi->dokter_tambahan,
                'alat' => $tipe_operasi->alat,
                'ruangan' => $tipe_operasi->ruangan,
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
            'operator' => 'required',
            'anestesi' => 'required',
            'resusitator' => 'required',
            'dokter_tambahan' => 'required',
            'alat' => 'required',
            'ruangan' => 'required',
        ]);

        try {
            $store = TipeOperasi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tipe' => 'required',
            'operator' => 'required',
            'anestesi' => 'required',
            'resusitator' => 'required',
            'dokter_tambahan' => 'required',
            'alat' => 'required',
            'ruangan' => 'required',
        ]);

        try {
            $tipe_operasi = TipeOperasi::find($id);
            $tipe_operasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePerColumn(Request $request, $id)
    {


        try {
            $tipe_operasi = TipeOperasi::find($id);
            $tipe_operasi->update([
                $request->field => $request->value,
            ]);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tipe_operasi = TipeOperasi::find($id);
            $tipe_operasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
