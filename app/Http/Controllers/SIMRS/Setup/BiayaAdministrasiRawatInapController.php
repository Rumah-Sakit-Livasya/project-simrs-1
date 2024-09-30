<?php

namespace App\Http\Controllers\SIMRS\Setup;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\Setup\BiayaAdministrasiRawatInap;
use Illuminate\Http\Request;

class BiayaAdministrasiRawatInapController extends Controller
{
    public function index()
    {
        $tarif = BiayaAdministrasiRawatInap::all();
        $group_penjamin = GroupPenjamin::all();
        return view('pages.simrs.master-data.setup.biaya-administrasi-ranap.index', compact('tarif', 'group_penjamin'));
    }

    public function update(Request $request)
    {
        try {
            // Ambil semua input dari request
            $persentase = $request->input('persentase');
            $minTarif = $request->input('min_tarif');
            $maxTarif = $request->input('max_tarif');

            // Loop untuk update berdasarkan index (group_penjamin_id)
            foreach ($persentase as $groupPenjaminId => $value) {
                // Temukan record berdasarkan group_penjamin_id
                $biayaAdministrasiRanap = BiayaAdministrasiRawatInap::find($groupPenjaminId);

                if ($biayaAdministrasiRanap) {
                    // Update nilai persentase, min_tarif, dan max_tarif
                    $biayaAdministrasiRanap->update([
                        'persentase' => $value,
                        'min_tarif' => $minTarif[$groupPenjaminId],
                        'max_tarif' => $maxTarif[$groupPenjaminId],
                    ]);
                }
            }
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
