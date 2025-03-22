<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Room;
use App\Models\SIMRS\TarifKelasRawat;
use Illuminate\Http\Request;

class KelasRawatController extends Controller
{
    public function index()
    {
        $kelas_rawat = KelasRawat::where('id', '!=', 1)->orderBy('urutan')->get();
        return view('pages.simrs.master-data.setup.kelas-rawat.index', compact('kelas_rawat'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kelas' => 'required',
            'urutan' => 'required',
            'keterangan' => 'required',
            'isICU' => 'nullable',
        ]);

        try {
            // Store the new KelasRawat
            $kelasRawat = KelasRawat::create($validatedData);

            // Retrieve all GroupPenjamin records
            $groupPenjamins = GroupPenjamin::all();

            // Loop through each GroupPenjamin and create a TarifKelasRawat record
            foreach ($groupPenjamins as $groupPenjamin) {
                TarifKelasRawat::create([
                    'kelas_rawat_id' => $kelasRawat->id,
                    'group_penjamin_id' => $groupPenjamin->id,
                    'tarif' => 0,
                    // Add any other fields required for TarifKelasRawat
                ]);
            }

            return response()->json(['message' => 'Kelas Rawat berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getKelas($id)
    {
        try {
            $kelas_rawat = KelasRawat::findOrFail($id);

            return response()->json([
                'kelas' => $kelas_rawat->kelas,
                'urutan' => $kelas_rawat->urutan,
                'keterangan' => $kelas_rawat->keterangan,
                'isICU' => $kelas_rawat->isICU,
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

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kelas' => 'required',
            'urutan' => 'required',
            'keterangan' => 'required',
            'isICU' => 'nullable',
        ]);

        try {
            $kelas_rawat = KelasRawat::findOrFail($id);
            $kelas_rawat->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $kelas_rawat = KelasRawat::find($id);
            $kelas_rawat->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
