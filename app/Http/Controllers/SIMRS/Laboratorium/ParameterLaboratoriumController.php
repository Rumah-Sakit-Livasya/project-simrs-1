<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\GrupParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\TipeLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use Illuminate\Http\Request;

class ParameterLaboratoriumController extends Controller
{
    public function index()
    {
        $parameter = ParameterLaboratorium::all();
        $grup_parameter = GrupParameterLaboratorium::all();
        $kategori = KategoriLaboratorium::all();
        $tipe = TipeLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.parameter', compact('parameter', 'grup_parameter', 'kategori', 'tipe'));
    }

    public function getParameter($id)
    {
        try {
            $parameter_laboratorium = ParameterLaboratorium::findOrFail($id);

            return response()->json([
                'grup_parameter_laboratorium_id' => $parameter_laboratorium->grup_parameter_laboratorium_id,
                'kategori_laboratorium_id' => $parameter_laboratorium->kategori_laboratorium_id,
                'parameter' => $parameter_laboratorium->parameter,
                'kode' => $parameter_laboratorium->kode,
                'is_reverse' => $parameter_laboratorium->is_reverse,
                'is_kontras' => $parameter_laboratorium->is_kontras,
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
            'grup_parameter_laboratorium_id' => 'required',
            'kategori_laboratorium_id' => 'required',
            'tipe_laboratorium_id' => 'required',
            'parameter' => 'required',
            'satuan' => 'nullable',
            'status' => 'nullable',
            'is_hasil' => 'nullable',
            'is_order' => 'nullable',
            'tipe_hasil' => 'nullable',
            'metode' => 'nullable',
            'no_urut' => 'nullable',
        ]);

        $validatedData['is_hasil'] = $request->is_hasil === "on" ? 1 : 0;
        $validatedData['is_order'] = $request->is_order === "on" ? 1 : 0;
        $lastKode = \DB::table('parameter_laboratorium')->max('kode');
        $validatedData['kode'] = $lastKode ? $lastKode + 1 : 1;

        try {
            $store = ParameterLaboratorium::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grup_parameter_laboratorium_id' => 'required',
            'kategori_laboratorium_id' => 'required',
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
            $grup_parameter_laboratorium = ParameterLaboratorium::find($id);
            $grup_parameter_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_parameter_laboratorium = ParameterLaboratorium::find($id);
            $grup_parameter_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
