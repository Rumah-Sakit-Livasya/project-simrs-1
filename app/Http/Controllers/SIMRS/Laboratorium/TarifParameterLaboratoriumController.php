<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use Illuminate\Http\Request;

class TarifParameterLaboratoriumController extends Controller
{
    public function store(Request $request, $parameterId, $grupPenjaminId)
    {
        // Ambil semua input share_dr, share_rs, dan total dari request
        $shareDrs = $request->input('share_dr');
        $shareRss = $request->input('share_rs');
        $prasarana = $request->input('prasarana');
        $bhp = $request->input('bhp');
        $totals = $request->input('total');

        // Validasi input (jika diperlukan)
        $request->validate([
            'share_dr.*' => 'numeric',
            'share_rs.*' => 'numeric',
            'prasarana.*' => 'numeric',
            'bhp.*' => 'numeric',
            'total.*' => 'numeric',
        ]);

        // Loop melalui setiap item di share_dr untuk memperbarui atau menyimpan data
        foreach ($shareDrs as $id => $shareDr) {
            // Temukan atau buat record baru
            TarifParameterLaboratorium::updateOrCreate(
                [
                    'kelas_rawat_id' => $id,
                    'group_penjamin_id' => $grupPenjaminId,
                    'parameter_laboratorium_id' => $parameterId,
                ],
                [
                    'share_dr' => $shareDr,
                    'share_rs' => $shareRss[$id] ?? 0,
                    'prasarana' => $prasarana[$id] ?? 0,
                    'bhp' => $bhp[$id] ?? 0,
                    'total' => $totals[$id] ?? 0,
                ]
            );
        }

        return response()->json(['message' => 'Data berhasil diperbarui!']);
    }

    public function getTarifParameter(Request $request, $parameterId, $grupPenjaminId)
    {
        $tarif_parameter = TarifParameterLaboratorium::where('parameter_laboratorium_id', $parameterId)->where('group_penjamin_id', $grupPenjaminId)->get();
        return response()->json(['data' => $tarif_parameter]);
    }
}
