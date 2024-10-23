<?php

namespace App\Http\Controllers\SIMRS\ResumeMedisRajal;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use DateTime;
use Illuminate\Http\Request;

class ResumeMedisRajalController extends Controller
{

    public function getResumeMedis(Request $request, $type, $registration_number)
    {
        try {
            $registration = Registration::where('registration_number', $registration_number)->where('registration_type', $type)->first();
            $resume = $registration->resume_medis_rajal;
            if ($resume) {
                return response()->json($resume, 200);
            } else {

                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pasien' => 'required',
            'registration_id' => 'required',
            'medical_record_number' => 'required',
            'tgl_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required',
            'alasan_masuk_rs' => 'required',
            'cara_keluar' => 'required',
            'berat_lahir' => 'nullable',
            'anamnesa' => 'required',
            'diagnosa_utama' => 'required',
            'diagnosa_tambahan' => 'nullable',
            'tindakan_utama' => 'nullable',
            'tindakan_tambahan' => 'nullable',
            'is_ttd' => 'nullable',
        ]);

        try {
            if ($request->is_ttd == 1) {
                $date = DateTime::createFromFormat('d/m/Y', $request->tgl_lahir);
                $validatedData['tgl_lahir'] = $date->format('Y-m-d');
                $validatedData['pic_dokter'] = auth()->user()->id;
                $validatedData['awal_rencana_tindak_lanjut'] = json_encode($request->awal_rencana_tindak_lanjut);
                $validatedData['awal_evaluasi_penyakit'] = json_encode($request->awal_evaluasi_penyakit);
                $validatedData['awal_edukasi'] = json_encode($request->awal_edukasi);
                $validatedData['asesmen_dilakukan_melalui'] = json_encode($request->asesmen_dilakukan_melalui);
                $validatedData['user_id'] = auth()->user()->id;
                if ($request->action_type = 'final') {
                    $validatedData['is_final'] = 1;
                }

                $store = ResumeMedisRajal::create($validatedData);
                return response()->json(['message' => ' berhasil ditambahkan!'], 200);
            } else {
                return response()->json(['error' => 'Harap ttd dulu!'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
