<?php

namespace App\Http\Controllers\SIMRS\Setup;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\Setup\TarifRegistrasi;
use Illuminate\Http\Request;

class TarifRegistrasiController extends Controller
{
    public function index()
    {
        $tarif_registrasi = TarifRegistrasi::all();
        return view('pages.simrs.master-data.setup.tarif-registrasi-layanan.index', compact('tarif_registrasi'));
    }

    public function setTarif($id)
    {
        $tarif_registrasi = TarifRegistrasi::find($id);
        $grup_penjamin = GroupPenjamin::all();
        // dd($tarif_registrasi);
        return view('pages.simrs.master-data.setup.tarif-registrasi-layanan.tarif', compact('tarif_registrasi', 'grup_penjamin'));
    }

    public function getTarif($id)
    {
        try {
            $tarif_registrasi = TarifRegistrasi::findOrFail($id);

            return response()->json([
                'nama_tarif' => $tarif_registrasi->nama_tarif,
                'tipe' => $tarif_registrasi->tipe,
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
            'nama_tarif' => 'required',
            'tipe' => 'required',
        ]);

        try {
            $store = TarifRegistrasi::create($validatedData);
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
            $tarif_registrasi = TarifRegistrasi::find($id);
            $tarif_registrasi->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tarif_registrasi = TarifRegistrasi::find($id);
            $tarif_registrasi->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
