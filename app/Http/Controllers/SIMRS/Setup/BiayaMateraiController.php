<?php

namespace App\Http\Controllers\SIMRS\Setup;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Setup\BiayaMaterai;
use Illuminate\Http\Request;

class BiayaMateraiController extends Controller
{
    public function index()
    {
        $biaya_materai = BiayaMaterai::all();
        return view('pages.simrs.master-data.setup.biaya-materai.index', compact('biaya_materai'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'biaya_materai' => 'required',
            'min_tarif' => 'required',
            'max_tarif' => 'required',
        ]);

        try {
            $store = BiayaMaterai::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'biaya_materai' => 'required',
            'min_tarif' => 'required',
            'max_tarif' => 'required',
        ]);

        try {
            $update = BiayaMaterai::find($id);

            if ($update) {
                $update->update($validatedData);
            } else {
                return response()->json(['message' => 'data tidak ditemukan', 404]);
            }
            return response()->json(['message' => 'berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {

        try {
            $update = BiayaMaterai::find($id);

            if ($update) {
                $update->delete();
            } else {
                return response()->json(['message' => 'data tidak ditemukan', 404]);
            }
            return response()->json(['message' => 'berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBiayaMaterai(Request $request, $id)
    {
        try {
            $biaya_materai = BiayaMaterai::find($id);
            // $biaya_materai->update([
            //     'biaya_materai' => $biaya_materai->biaya_materai,
            //     'min_tarif' => $biaya_materai->min_tarif,
            //     'max_tarif' => $biaya_materai->max_tarif
            // ]);
            return response()->json($biaya_materai, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
