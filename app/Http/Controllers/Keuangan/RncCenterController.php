<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\RncCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RncCenterController extends Controller
{
    public function index()
    {
        $rncCenters = RncCenter::latest()->get();

        return view('app-type.keuangan.setup.revenue-costcenter.index', compact('rncCenters'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_rnc' => 'required|string|max:255|unique:rnc_centers,kode_rnc',
            'nama_rnc' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $rncCenter = RncCenter::create($request->all());
        return response()->json(['message' => 'Data berhasil ditambahkan.', 'data' => $rncCenter]);
    }

    public function update(Request $request, $id) // <-- PERUBAHAN DI SINI
    {
        $rncCenter = RncCenter::find($id);
        if (!$rncCenter) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_rnc' => 'required|string|max:255|unique:rnc_centers,kode_rnc,' . $rncCenter->id,
            'nama_rnc' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rncCenter->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui.', 'data' => $rncCenter]);
    }


    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 400);
        }
        RncCenter::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Data yang dipilih berhasil dihapus.']);
    }
}
