<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class PengkajianDokterRajalController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required',
            'pr' => 'required',
            'rr' => 'required',
            'bp' => 'required',
            'body_weight' => 'required',
            'body_height' => 'required',
            'temperatur' => 'required',
            'bmi' => 'required',
            'kat_bmi' => 'required',
            'sp02' => 'required',
            'diagnosa_keperawatan' => 'required',
            'rencana_tindak_lanjut' => 'required',

            //end section ttv
            'asesmen_dilakukan_melalui' => 'required',
            'awal_tgl_rajal' => 'required',
            'awal_jam_rajal' => 'required',
            'awal_keluhan' => 'required',
            'awal_riwayat_penyakit_sekarang' => 'required',
            'awal_riwayat_penyakit_dahulu' => 'nullable',
            'awal_riwayat_penyakit_keluarga' => 'nullable',
            'awal_riwayat_alergi_obat' => 'required',
            'awal_riwayat_alergi_obat_lain' => 'nullable',
            'awal_pemeriksaan_fisik' => 'required',
            'awal_pemeriksaan_penunjang' => 'required',
            'awal_diagnosa_kerja' => 'required',
            'awal_diagnosa_banding' => 'required',
            'awal_terapi_tindakan' => 'required',
            'awal_edukasi' => 'required',
            'awal_evaluasi_penyakit' => 'nullable',
            'awal_rencana_tindak_lanjut' => 'nullable',
            'is_verified' => 'nullable',
            'is_final' => 'nullable',
        ]);

        try {
            $validatedData['is_verified'] = 1;
            $validatedData['awal_rencana_tindak_lanjut'] = json_encode($request->awal_rencana_tindak_lanjut);
            $validatedData['awal_evaluasi_penyakit'] = json_encode($request->awal_evaluasi_penyakit);
            $validatedData['awal_edukasi'] = json_encode($request->awal_edukasi);
            $validatedData['asesmen_dilakukan_melalui'] = json_encode($request->asesmen_dilakukan_melalui);
            $validatedData['user_id'] = auth()->user()->id;
            if ($request->action_type = 'final') {
                $validatedData['is_final'] = 1;
            }
            $store = PengkajianDokterRajal::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPengkajian(Request $request, $type, $registration_number)
    {
        try {
            $registration = Registration::where('registration_number', $registration_number)->where('registration_type', $type)->first();
            $pengkajian = $registration->pengkajian_dokter_rajal;
            if ($pengkajian) {
                return response()->json($pengkajian, 200);
            } else {

                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
