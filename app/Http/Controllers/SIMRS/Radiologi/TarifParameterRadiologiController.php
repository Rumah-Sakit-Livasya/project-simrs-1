<?php

namespace App\Http\Controllers\SIMRS\Radiologi;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use Illuminate\Http\Request;

class TarifParameterRadiologiController extends Controller
{
    public function store(Request $request, $parameterId, $grupPenjaminId)
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
            TarifParameterRadiologi::updateOrCreate(
                [
                    'kelas_rawat_id' => $id,
                    'group_penjamin_id' => $grupPenjaminId,
                    'parameter_radiologi_id' => $parameterId,
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

    public function getTarifParameter(Request $request, $parameterId, $grupPenjaminId)
    {
        $tarif_parameter = TarifParameterRadiologi::where('parameter_radiologi_id', $parameterId)->where('group_penjamin_id', $grupPenjaminId)->get();
        return response()->json(['data' => $tarif_parameter]);
    }
}
