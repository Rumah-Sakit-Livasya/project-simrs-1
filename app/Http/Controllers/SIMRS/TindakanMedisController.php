<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GrupTindakanMedis;
use App\Models\SIMRS\TindakanMedis;
use Illuminate\Http\Request;

class TindakanMedisController extends Controller
{
    public function index()
    {
        $tindakan_medis = TindakanMedis::all();
        $grup_tindakan = GrupTindakanMedis::get(['id', 'nama_grup']);
        return view('pages.simrs.master-data.layanan-medis.tindakan-medis', compact('tindakan_medis', 'grup_tindakan'));
    }

    public function getTindakan($id)
    {
        try {
            $tindakan_medis = TindakanMedis::findOrFail($id);

            return response()->json([
                'grup_tindakan_medis_id' => $tindakan_medis->grup_tindakan_medis_id,
                'kode' => $tindakan_medis->kode,
                'nama_tindakan' => $tindakan_medis->nama_tindakan,
                'nama_billing' => $tindakan_medis->nama_billing,
                'is_konsul' => $tindakan_medis->is_konsul,
                'auto_charge' => $tindakan_medis->auto_charge,
                'is_vaksin' => $tindakan_medis->is_vaksin,
                'mapping_rl_13' => $tindakan_medis->mapping_rl_13,
                'mapping_rl_34' => $tindakan_medis->mapping_rl_34,
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
            'grup_tindakan_medis_id' => 'required',
            'kode' => 'required',
            'nama_tindakan' => 'required',
            'nama_billing' => 'required',
            'is_konsul' => 'nullable',
            'auto_charge' => 'nullable',
            'is_vaksin' => 'nullable',
            'mapping_rl_13' => 'nullable',
            'mapping_rl_34' => 'nullable',
        ]);

        try {
            $store = TindakanMedis::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grup_tindakan_medis_id' => 'required',
            'kode' => 'required',
            'nama_tindakan' => 'required',
            'nama_billing' => 'required',
            'is_konsul' => 'nullable',
            'auto_charge' => 'nullable',
            'is_vaksin' => 'nullable',
            'mapping_rl_13' => 'nullable',
            'mapping_rl_34' => 'nullable',
        ]);

        try {
            $grup_tindakan_medis = TindakanMedis::find($id);
            $grup_tindakan_medis->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_tindakan_medis = TindakanMedis::find($id);
            $grup_tindakan_medis->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
