<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GrupParameterRadiologi;
use Illuminate\Http\Request;

class GrupParameterRadiologiController extends Controller
{
    public function index()
    {
        $grup_parameter_radiologi = GrupParameterRadiologi::all();
        return view('pages.simrs.master-data.penunjang-medis.grup-parameter-radiologi', compact('grup_parameter_radiologi'));
    }

    public function getGrupParameter($id)
    {
        try {
            $grup_parameter_radiologi = GrupParameterRadiologi::findOrFail($id);

            return response()->json([
                'no_urut' => $grup_parameter_radiologi->no_urut,
                'nama_grup' => $grup_parameter_radiologi->nama_grup,
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
            'no_urut' => 'required',
            'nama_grup' => 'required',
        ]);

        try {
            $store = GrupParameterRadiologi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'no_urut' => 'required',
            'nama_grup' => 'required',
        ]);

        try {
            $grup_grup_parameter_radiologi = GrupParameterRadiologi::find($id);
            $grup_grup_parameter_radiologi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_grup_parameter_radiologi = GrupParameterRadiologi::find($id);
            $grup_grup_parameter_radiologi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
