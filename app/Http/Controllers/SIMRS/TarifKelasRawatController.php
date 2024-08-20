<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\TarifKelasRawat;
use Illuminate\Http\Request;

class TarifKelasRawatController extends Controller
{
    public function getTarif($id)
    {
        try {
            // Mengambil semua tarif terkait dengan kelas_rawat_id tertentu
            $tarifList = TarifKelasRawat::where('kelas_rawat_id', $id)
                ->with('group_penjamin') // Pastikan relasi dengan Group_penjamin di-load
                ->get();

            // Mengembalikan response dengan daftar tarif
            return response()->json($tarifList, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            // Mendapatkan data tarif dari request
            $tarifs = $request->input('tarif'); // Format: tarif[group_penjamin_id] => amount

            foreach ($tarifs as $groupPenjaminId => $amount) {
                // Mengambil tarif yang sesuai dengan ID grup penjamin dan kelas rawat
                $tarif = TarifKelasRawat::where('group_penjamin_id', $groupPenjaminId)
                    ->where('kelas_rawat_id', $request->input('kelas_rawat_id'))
                    ->first();


                if ($tarif) {
                    // Memperbarui tarif
                    $tarif->tarif = $amount;
                    $tarif->save();
                }
            }

            return response()->json(['message' => 'Tarif berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
