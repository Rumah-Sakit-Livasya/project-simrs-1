<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\ParameterRadiologi;
use Illuminate\Http\Request;

class ParameterRadiologiController extends Controller
{
    public function index()
    {
        $parameter_radiologi = ParameterRadiologi::all();
        return view('pages.simrs.master-data.penunjang-medis.parameter-radiologi', compact('parameter_radiologi'));
    }

    public function getParameter($id)
    {
        try {
            $parameter_radiologi = ParameterRadiologi::findOrFail($id);

            return response()->json([
                'grup_parameter_radiologi_id' => $parameter_radiologi->grup_parameter_radiologi_id,
                'kategori_radiologi_id' => $parameter_radiologi->kategori_radiologi_id,
                'parameter' => $parameter_radiologi->parameter,
                'status' => $parameter_radiologi->status,
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
            'grup_parameter_radiologi_id' => 'required',
            'kategori_radiologi_id' => 'required',
            'parameter' => 'required',
            'status' => 'nullable',
        ]);

        try {
            $store = ParameterRadiologi::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grup_parameter_radiologi_id' => 'required',
            'kategori_radiologi_id' => 'required',
            'parameter' => 'required',
            'status' => 'nullable',
        ]);

        try {
            $grup_parameter_radiologi = ParameterRadiologi::find($id);
            $grup_parameter_radiologi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_parameter_radiologi = ParameterRadiologi::find($id);
            $grup_parameter_radiologi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
