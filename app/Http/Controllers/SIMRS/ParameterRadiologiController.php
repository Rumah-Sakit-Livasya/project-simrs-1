<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GrupParameterRadiologi;
use App\Models\SIMRS\KategoriRadiologi;
use App\Models\SIMRS\ParameterRadiologi;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class ParameterRadiologiController extends Controller
{
    public function index()
    {
        $grup_parameter_radiologi = GrupParameterRadiologi::all();
        $kategori_radiologi = KategoriRadiologi::all();
        $parameter_radiologi = ParameterRadiologi::all();
        return view('pages.simrs.master-data.penunjang-medis.parameter-radiologi', compact('grup_parameter_radiologi', 'kategori_radiologi', 'parameter_radiologi'));
    }

    public function getParameter($id)
    {
        try {
            $parameter_radiologi = ParameterRadiologi::findOrFail($id);

            return response()->json([
                'grup_parameter_radiologi_id' => $parameter_radiologi->grup_parameter_radiologi_id,
                'kategori_radiologi_id' => $parameter_radiologi->kategori_radiologi_id,
                'parameter' => $parameter_radiologi->parameter,
                'kode' => $parameter_radiologi->kode,
                'is_reverse' => $parameter_radiologi->is_reverse,
                'is_kontras' => $parameter_radiologi->is_kontras,
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
            'kode' => 'required|unique:parameter_radiologi,kode',
            'parameter' => 'required',
            'is_kontras' => 'nullable',
            'is_reverse' => 'nullable',
        ]);

        $validatedData['is_kontras'] = $request->is_kontras === "on" ? 1 : 0;
        $validatedData['is_reverse'] = $request->is_reverse === "on" ? 1 : 0;

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
            'kode' => 'required',
            'parameter' => 'required',
            'is_kontras' => 'nullable',
            'is_reverse' => 'nullable',
        ]);

        if ($request->has('is_kontras')) {
            $validatedData['is_kontras'] = $request->is_kontras === "on" ? 1 : 0;
        } else {
            $validatedData['is_kontras'] = 0;
        }
        if ($request->has('is_reverse')) {
            $validatedData['is_reverse'] = $request->is_reverse === "on" ? 1 : 0;
        } else {
            $validatedData['is_reverse'] = 0;
        }

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
