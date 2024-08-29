<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use GuzzleHttp\Psr7\Request;

class NilaiNormalLaboratoriumController extends Controller
{
    public function index()
    {
        $parameter = ParameterLaboratorium::all();
        $nilai_parameter = NilaiNormalLaboratorium::all();
        return view('pages.simrs.master-data.penunjang-medis.laboratorium.nilai-parameter', compact('parameter', 'nilai_parameter'));
    }

    public function getParameter($id)
    {
        try {
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::findOrFail($id);

            return response()->json([
                'parameter_laboratorium_id' => $nilai_parameter_laboratorium->parameter_laboratorium_id,
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
            'parameter_laboratorium_id' => 'required',
            // 'kategori_laboratorium_id' => 'required',
            // 'tipe_laboratorium_id' => 'required',
            // 'parameter' => 'required',
            // 'satuan' => 'nullable',
            // 'status' => 'nullable',
            // 'is_hasil' => 'nullable',
            // 'is_order' => 'nullable',
            // 'tipe_hasil' => 'nullable',
            // 'metode' => 'nullable',
            // 'no_urut' => 'nullable',
        ]);

        $validatedData['is_hasil'] = $request->is_hasil === "on" ? 1 : 0;
        $validatedData['is_order'] = $request->is_order === "on" ? 1 : 0;
        $lastKode = \DB::table('nilai_parameter_laboratorium')->max('kode');
        $validatedData['kode'] = $lastKode ? $lastKode + 1 : 1;

        try {
            $store = NilaiNormalLaboratorium::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'parameter_laboratorium_id' => 'required',
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
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::find($id);
            $nilai_parameter_laboratorium->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $nilai_parameter_laboratorium = NilaiNormalLaboratorium::find($id);
            $nilai_parameter_laboratorium->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
