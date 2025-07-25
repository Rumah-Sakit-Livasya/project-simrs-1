<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Persalinan\TarifPersalinan;
use Illuminate\Http\Request;

class TarifPersalinanController extends Controller
{
    public function store(Request $request, $persalinanId, $grupPenjaminId)
    {
        $kelasRawatRaw = $request->input('kelas_rawat_ids')[0] ?? '';
        $kelasRawatIds = explode(',', $kelasRawatRaw);

        $grupPenjaminId = $request->input('grup_penjamin_id');
        $persalinanId = $persalinanId; // dari parameter route

        foreach ($kelasRawatIds as $kelasRawatId) {
            TarifPersalinan::updateOrCreate(
                [
                    'kelas_rawat_id' => $kelasRawatId,
                    'group_penjamin_id' => $grupPenjaminId,
                    'persalinan_id' => $persalinanId,
                ],
                [
                    'operator_dokter' => $request->input("operator_dokter.$kelasRawatId", 0),
                    'operator_rs' => $request->input("operator_rs.$kelasRawatId", 0),
                    'operator_prasarana' => $request->input("operator_prasarana.$kelasRawatId", 0),
                    'ass_operator_dokter' => $request->input("ass_operator_dokter.$kelasRawatId", 0),
                    'ass_operator_rs' => $request->input("ass_operator_rs.$kelasRawatId", 0),
                    'anastesi_dokter' => $request->input("anastesi_dokter.$kelasRawatId", 0),
                    'anastesi_rs' => $request->input("anastesi_rs.$kelasRawatId", 0),
                    'ass_anastesi_dokter' => $request->input("ass_anastesi_dokter.$kelasRawatId", 0),
                    'ass_anastesi_rs' => $request->input("ass_anastesi_rs.$kelasRawatId", 0),
                    'resusitator_dokter' => $request->input("resusitator_dokter.$kelasRawatId", 0),
                    'resusitator_rs' => $request->input("resusitator_rs.$kelasRawatId", 0),
                    'umum_dokter' => $request->input("umum_dokter.$kelasRawatId", 0),
                    'umum_rs' => $request->input("umum_rs.$kelasRawatId", 0),
                    'ruang' => $request->input("ruang.$kelasRawatId", 0),
                ]
            );
        }

        return response()->json(['message' => 'Tarif persalinan berhasil disimpan.']);
    }

    public function getTarifPersalinan(Request $request, $persalinanId, $grupPenjaminId)
    {
        $tarif_persalinan = TarifPersalinan::where('persalinan_id', $persalinanId)
            ->where('group_penjamin_id', $grupPenjaminId)
            ->get();

        $data = $tarif_persalinan->map(function ($item) {
            return [
                'kelas_rawat_id' => $item->kelas_rawat_id,
                'operator_dokter' => $item->operator_dokter,
                'operator_rs' => $item->operator_rs,
                'operator_prasarana' => $item->operator_prasarana,
                'ass_operator_dokter' => $item->ass_operator_dokter,
                'ass_operator_rs' => $item->ass_operator_rs,
                'anastesi_dokter' => $item->anastesi_dokter,
                'anastesi_rs' => $item->anastesi_rs,
                'ass_anastesi_dokter' => $item->ass_anastesi_dokter,
                'ass_anastesi_rs' => $item->ass_anastesi_rs,
                'resusitator_dokter' => $item->resusitator_dokter,
                'resusitator_rs' => $item->resusitator_rs,
                'umum_dokter' => $item->umum_dokter,
                'umum_rs' => $item->umum_rs,
                'ruang' => $item->ruang,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
