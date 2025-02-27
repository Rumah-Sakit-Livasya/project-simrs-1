<?php

namespace App\Http\Controllers\SIMRS\Setup;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\Setup\HargaTarifRegistrasi;
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
        $harga = $tarif_registrasi->harga_tarif->where('group_penjamin_id', 1)->first();
        return view('pages.simrs.master-data.setup.tarif-registrasi-layanan.tarif', compact('tarif_registrasi', 'grup_penjamin', 'harga'));
    }

    public function setDepartement($id)
    {
        $tarif_registrasi = TarifRegistrasi::find($id);
        $departement = Departement::all();
        $harga = $tarif_registrasi->harga_tarif->where('group_penjamin_id', 1)->first();
        return view('pages.simrs.master-data.setup.tarif-registrasi-layanan.departement', compact('tarif_registrasi', 'departement', 'harga'));
    }

    public function storeDepartments(Request $request, $tarifRegistId)
    {
        try {
            $tarif_registrasi = TarifRegistrasi::findOrFail($tarifRegistId);
            $departments = $request->input('departments', []);

            // Sync the departments (this will remove existing associations and add new ones)
            $tarif_registrasi->departements()->sync($departments);

            return response()->json(['message' => 'Departemen berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTarif($tarifRegistId, $grupPenjaminId)
    {
        try {
            $tarif_registrasi = HargaTarifRegistrasi::where('tarif_registrasi_id', $tarifRegistId)->where('group_penjamin_id', $grupPenjaminId)->first();

            return response()->json($tarif_registrasi, 200);
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

    public function storeTarif(Request $request, $tarifRegistId, $grupPenjaminId)
    {
        // Ambil semua input share_dr, share_rs, dan total dari request
        $harga = $request->harga;
        $group_penjamin_id = $grupPenjaminId;

        HargaTarifRegistrasi::updateOrCreate(
            [
                'tarif_registrasi_id' => $tarifRegistId,
                'group_penjamin_id' => $grupPenjaminId,
            ],
            [
                'harga' => $harga
            ]
        );

        return response()->json(['message' => 'Data berhasil diperbarui!']);
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
