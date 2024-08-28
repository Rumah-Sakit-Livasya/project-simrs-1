<?php

namespace App\Http\Controllers\SIMRS\Peralatan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Peralatan\TarifPeralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        $peralatan = Peralatan::all();
        return view('pages.simrs.master-data.peralatan.index', compact('peralatan'));
    }

    public function getPeralatan($id)
    {
        try {
            $peralatan = Peralatan::findOrFail($id);

            return response()->json([
                'kode' => $peralatan->kode,
                'nama' => $peralatan->nama,
                'satuan_pakai' => $peralatan->satuan_pakai,
                'is_req_dokter' => $peralatan->is_req_dokter,
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
            'kode' => 'required',
            'nama' => 'required',
            'satuan_pakai' => 'required',
            'is_req_dokter' => 'required',
        ]);


        try {
            $store = Peralatan::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'satuan_pakai' => 'required',
            'is_req_dokter' => 'required',
        ]);

        try {
            $peralatan = Peralatan::find($id);
            $peralatan->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $peralatan = Peralatan::find($id);
            $peralatan->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeTarif(Request $request, $peralatanId, $grupPenjaminId)
    {
        // Ambil semua input share_dr, share_rs, dan total dari request
        $shareDrs = $request->input('share_dr');
        $shareRss = $request->input('share_rs');
        $totals = $request->input('total');

        // Validasi input (jika diperlukan)
        $request->validate([
            'share_dr.*' => 'numeric',
            'share_rs.*' => 'numeric',
            'total.*' => 'numeric',
        ]);

        // Loop melalui setiap item di share_dr untuk memperbarui atau menyimpan data
        foreach ($shareDrs as $id => $shareDr) {
            // Temukan atau buat record baru
            TarifPeralatan::updateOrCreate(
                [
                    'kelas_rawat_id' => $id,
                    'group_penjamin_id' => $grupPenjaminId,
                    'peralatan_id' => $peralatanId,
                ],
                [
                    'share_dr' => $shareDr,
                    'share_rs' => $shareRss[$id] ?? 0,
                    'total' => $totals[$id] ?? 0,
                ]
            );
        }

        return response()->json(['message' => 'Data berhasil diperbarui!']);
    }

    public function getTarifPeralatan(Request $request, $peralatanId, $grupPenjaminId)
    {
        $tarif_parameter = TarifPeralatan::where('peralatan_id', $peralatanId)->where('group_penjamin_id', $grupPenjaminId)->get();
        return response()->json(['data' => $tarif_parameter]);
    }

    public function tarifPeralatan($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $grup_penjamin = GroupPenjamin::all();
        $kelas_rawat = KelasRawat::select('id', 'kelas')->get();

        return view('pages.simrs.master-data.peralatan.tarif-peralatan', compact('peralatan', 'grup_penjamin', 'kelas_rawat'));
    }
}
