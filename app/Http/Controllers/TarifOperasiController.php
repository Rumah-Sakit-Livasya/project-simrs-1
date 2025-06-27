<?php

namespace App\Http\Controllers;

use App\Models\SIMRS\Operasi\TarifOperasi;
use Illuminate\Http\Request;

class TarifOperasiController extends Controller
{
    public function store(Request $request, $tindakanOperasiId, $grupPenjaminId)
    {
        $kelasRawatRaw = $request->input('kelas_rawat_ids')[0] ?? '';
        $kelasRawatIds = explode(',', $kelasRawatRaw);

        $tipeDokter = $request->input('tipe_dokter', 'Standar');

        foreach ($kelasRawatIds as $kelasRawatId) {
            TarifOperasi::updateOrCreate(
                [
                    'kelas_rawat_id' => $kelasRawatId,
                    'group_penjamin_id' => $grupPenjaminId,
                    'tindakan_operasi_id' => $tindakanOperasiId,
                    // 'tipe_dokter' => $tipeDokter,
                ],
                [
                    // Operator
                    'operator_dokter' => $request->input("operator_dokter.$kelasRawatId", 0),
                    'operator_rs' => $request->input("operator_rs.$kelasRawatId", 0),
                    'operator_anastesi_dokter' => $request->input("operator_anastesi_dokter.$kelasRawatId", 0),
                    'operator_anastesi_rs' => $request->input("operator_anastesi_rs.$kelasRawatId", 0),
                    'operator_resusitator_dokter' => $request->input("operator_resusitator_dokter.$kelasRawatId", 0),
                    'operator_resusitator_rs' => $request->input("operator_resusitator_rs.$kelasRawatId", 0),

                    // Asisten Operator 1~3
                    'asisten_operator_1_dokter' => $request->input("asisten_operator_1_dokter.$kelasRawatId", 0),
                    'asisten_operator_1_rs' => $request->input("asisten_operator_1_rs.$kelasRawatId", 0),
                    'asisten_operator_2_dokter' => $request->input("asisten_operator_2_dokter.$kelasRawatId", 0),
                    'asisten_operator_2_rs' => $request->input("asisten_operator_2_rs.$kelasRawatId", 0),
                    'asisten_operator_3_dokter' => $request->input("asisten_operator_3_dokter.$kelasRawatId", 0),
                    'asisten_operator_3_rs' => $request->input("asisten_operator_3_rs.$kelasRawatId", 0),

                    // Asisten Anastesi 1~2
                    'asisten_anastesi_1_dokter' => $request->input("asisten_anastesi_1_dokter.$kelasRawatId", 0),
                    'asisten_anastesi_1_rs' => $request->input("asisten_anastesi_1_rs.$kelasRawatId", 0),
                    'asisten_anastesi_2_dokter' => $request->input("asisten_anastesi_2_dokter.$kelasRawatId", 0),
                    'asisten_anastesi_2_rs' => $request->input("asisten_anastesi_2_rs.$kelasRawatId", 0),

                    // Dokter Tambahan 1~5
                    'dokter_tambahan_1_dokter' => $request->input("dokter_tambahan_1_dokter.$kelasRawatId", 0),
                    'dokter_tambahan_1_rs' => $request->input("dokter_tambahan_1_rs.$kelasRawatId", 0),
                    'dokter_tambahan_2_dokter' => $request->input("dokter_tambahan_2_dokter.$kelasRawatId", 0),
                    'dokter_tambahan_2_rs' => $request->input("dokter_tambahan_2_rs.$kelasRawatId", 0),
                    'dokter_tambahan_3_dokter' => $request->input("dokter_tambahan_3_dokter.$kelasRawatId", 0),
                    'dokter_tambahan_3_rs' => $request->input("dokter_tambahan_3_rs.$kelasRawatId", 0),
                    'dokter_tambahan_4_dokter' => $request->input("dokter_tambahan_4_dokter.$kelasRawatId", 0),
                    'dokter_tambahan_4_rs' => $request->input("dokter_tambahan_4_rs.$kelasRawatId", 0),
                    'dokter_tambahan_5_dokter' => $request->input("dokter_tambahan_5_dokter.$kelasRawatId", 0),
                    'dokter_tambahan_5_rs' => $request->input("dokter_tambahan_5_rs.$kelasRawatId", 0),

                    // Ruang & Alat
                    'ruang_operasi' => $request->input("ruang_operasi.$kelasRawatId", 0),
                    'bmhp' => $request->input("bmhp.$kelasRawatId", 0),
                    'alat_dokter' => $request->input("alat_dokter.$kelasRawatId", 0),
                    'alat_rs' => $request->input("alat_rs.$kelasRawatId", 0),
                ]
            );
        }

        return response()->json(['message' => 'Tarif operasi berhasil disimpan.']);
    }

    public function getTarifOperasi(Request $request, $tindakanOperasiId, $grupPenjaminId)
    {
        $tarifOperasi = TarifOperasi::where('tindakan_operasi_id', $tindakanOperasiId)
            ->where('group_penjamin_id', $grupPenjaminId)
            ->get();

        $data = $tarifOperasi->map(function ($item) {
            return [
                'kelas_rawat_id' => $item->kelas_rawat_id,

                'operator_dokter' => $item->operator_dokter,
                'operator_rs' => $item->operator_rs,
                'operator_anastesi_dokter' => $item->operator_anastesi_dokter,
                'operator_anastesi_rs' => $item->operator_anastesi_rs,
                'operator_resusitator_dokter' => $item->operator_resusitator_dokter,
                'operator_resusitator_rs' => $item->operator_resusitator_rs,

                'asisten_operator_1_dokter' => $item->asisten_operator_1_dokter,
                'asisten_operator_1_rs' => $item->asisten_operator_1_rs,
                'asisten_operator_2_dokter' => $item->asisten_operator_2_dokter,
                'asisten_operator_2_rs' => $item->asisten_operator_2_rs,
                'asisten_operator_3_dokter' => $item->asisten_operator_3_dokter,
                'asisten_operator_3_rs' => $item->asisten_operator_3_rs,

                'asisten_anastesi_1_dokter' => $item->asisten_anastesi_1_dokter,
                'asisten_anastesi_1_rs' => $item->asisten_anastesi_1_rs,
                'asisten_anastesi_2_dokter' => $item->asisten_anastesi_2_dokter,
                'asisten_anastesi_2_rs' => $item->asisten_anastesi_2_rs,

                'dokter_tambahan_1_dokter' => $item->dokter_tambahan_1_dokter,
                'dokter_tambahan_1_rs' => $item->dokter_tambahan_1_rs,
                'dokter_tambahan_2_dokter' => $item->dokter_tambahan_2_dokter,
                'dokter_tambahan_2_rs' => $item->dokter_tambahan_2_rs,
                'dokter_tambahan_3_dokter' => $item->dokter_tambahan_3_dokter,
                'dokter_tambahan_3_rs' => $item->dokter_tambahan_3_rs,
                'dokter_tambahan_4_dokter' => $item->dokter_tambahan_4_dokter,
                'dokter_tambahan_4_rs' => $item->dokter_tambahan_4_rs,
                'dokter_tambahan_5_dokter' => $item->dokter_tambahan_5_dokter,
                'dokter_tambahan_5_rs' => $item->dokter_tambahan_5_rs,

                'ruang_operasi' => $item->ruang_operasi,
                'bmhp' => $item->bmhp,
                'alat_dokter' => $item->alat_dokter,
                'alat_rs' => $item->alat_rs,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
