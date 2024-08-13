<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use Illuminate\Http\Request;

class GrupParameterLaboratoriumController extends Controller
{
    public function index()
    {
        $grup_parameter_laboratorium = GrupParameterLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.grup-parameter-lab', compact('grup_parameter_laboratorium'));
    }

    public function getGrupParameter($id)
    {
        try {
            $grup_parameter_laboratorium = GrupParameterLaboratorium::findOrFail($id);

            return response()->json([
                'no_urut' => $grup_parameter_laboratorium->no_urut,
                'nama_grup' => $grup_parameter_laboratorium->nama_grup,
                'kode_order' => $grup_parameter_laboratorium->kode_order,
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
            'kode_order' => 'required',
        ]);

        try {
            $store = GrupParameterLaboratorium::create($validatedData);
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
            'kode_order' => 'required',
        ]);

        try {
            $grup_grup_parameter_laboratorium = GrupParameterLaboratorium::find($id);
            $grup_grup_parameter_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_grup_parameter_laboratorium = GrupParameterLaboratorium::find($id);
            $grup_grup_parameter_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
