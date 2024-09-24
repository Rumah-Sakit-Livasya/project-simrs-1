<?php

namespace App\Http\Controllers\SIMRS\HargaJual;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\HargaJual\MarginHargaJual;
use App\Models\SIMRS\KelasRawat;
use Illuminate\Http\Request;

class MarginHargaJualController extends Controller
{
    public function index()
    {
        $margin_harga_jual = MarginHargaJual::all();
        $grup_penjamin = GroupPenjamin::all();
        $kelas_rawat = KelasRawat::all();
        return view('pages.simrs.master-data.harga-jual.margin-harga-jual', compact('margin_harga_jual', 'grup_penjamin', 'kelas_rawat'));
    }

    public function storeTarif(Request $request)
    {
        $validatedData = $request->validate([
            'group_penjamin_id' => 'required',
            'margin*' => 'required',
        ]);

        try {
            // Ambil semua input share_dr, share_rs, dan total dari request
            $margin = $request->input('margin');
            $group_penjamin_id = $request->input('group_penjamin_id');

            // Loop melalui setiap item di share_dr untuk memperbarui atau menyimpan data
            foreach ($margin as $id => $m) {
                // Temukan atau buat record baru
                MarginHargaJual::updateOrCreate(
                    [
                        'kelas_rawat_id' => $id,
                        'group_penjamin_id' => $group_penjamin_id,
                    ],
                    [
                        'margin' => $m
                    ]
                );
            }

            return response()->json(['message' => 'Data berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTarif($grupPenjaminId)
    {
        try {
            $margin_harga_jual = MarginHargaJual::where('group_penjamin_id', $grupPenjaminId)->get();

            return response()->json($margin_harga_jual, 200);
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

    // public function update(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'nama_tarif' => 'required',
    //         'tipe' => 'required',
    //     ]);

    //     try {
    //         $margin_harga_jual = MarginHargaJual::find($id);
    //         $margin_harga_jual->update($validatedData);
    //         return response()->json(['message' => ' berhasil diupdate!'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function delete($id)
    // {
    //     try {
    //         $margin_harga_jual = MarginHargaJual::find($id);
    //         $margin_harga_jual->delete();
    //         return response()->json(['message' => ' berhasil dihapus'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
}
