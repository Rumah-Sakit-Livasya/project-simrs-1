<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GrupTindakanMedis;
use Illuminate\Http\Request;

class GrupTindakanMedisController extends Controller
{
    public function index()
    {
        // dd(session()->all());
        $grup_tindakan_medis = GrupTindakanMedis::all();
        $departements = Departement::all();
        return view('pages.simrs.master-data.layanan-medis.grup-tindakan-medis', compact('grup_tindakan_medis', 'departements'));
    }

    public function getGrupTindakan($id)
    {
        try {
            $grup_tindakan_medis = GrupTindakanMedis::findOrFail($id);

            return response()->json([
                'departement_id' => $grup_tindakan_medis->departement_id,
                'nama_grup' => $grup_tindakan_medis->nama_grup,
                'coa_pendapatan' => $grup_tindakan_medis->coa_pendapatan,
                'coa_prasarana' => $grup_tindakan_medis->coa_prasarana,
                'coa_bhp' => $grup_tindakan_medis->coa_bhp,
                'coa_biaya' => $grup_tindakan_medis->coa_biaya,
                'status' => $grup_tindakan_medis->status,
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
            'departement_id' => 'required',
            'nama_grup' => 'required',
            'status' => 'required',
            'coa_pendapatan' => 'required',
            'coa_prasarana' => 'nullable',
            'coa_bhp' => 'nullable',
            'coa_biaya' => 'required',
        ]);

        try {
            $store = GrupTindakanMedis::create($validatedData);
            return response()->json(['message' => 'Grup berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'departement_id' => 'required',
            'nama_grup' => 'required',
            'status' => 'required',
            'coa_pendapatan' => 'required',
            'coa_prasarana' => 'nullable',
            'coa_bhp' => 'nullable',
            'coa_biaya' => 'required',
        ]);

        try {
            $grup_tindakan_medis = GrupTindakanMedis::find($id);
            $grup_tindakan_medis->update($validatedData);
            return response()->json(['message' => 'Grup berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_tindakan_medis = GrupTindakanMedis::find($id);
            $grup_tindakan_medis->delete();
            return response()->json(['message' => 'Grup berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
