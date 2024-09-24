<?php

namespace App\Http\Controllers\SIMRS\Setup;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\Setup\BiayaAdministrasiRawatInap;
use Illuminate\Http\Request;

class BiayaAdministrasiRawatInapController extends Controller
{
    public function index()
    {
        $tarif = BiayaAdministrasiRawatInap::all();
        $group_penjamin = GroupPenjamin::all();
        return view('pages.simrs.master-data.setup.biaya-administrasi-ranap.index', compact('tarif', 'group_penjamin'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_tarif' => 'required',
            'tipe' => 'required',
        ]);

        try {
            $store = BiayaAdministrasiRawatInap::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_tarif' => 'required',
            'tipe' => 'required',
        ]);

        try {
            $tarif_registrasi = BiayaAdministrasiRawatInap::find($id);
            $tarif_registrasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tarif_registrasi = BiayaAdministrasiRawatInap::find($id);
            $tarif_registrasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
