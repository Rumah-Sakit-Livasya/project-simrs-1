<?php

namespace App\Http\Controllers\SIMRS\ResumeMedisRajal;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use Illuminate\Http\Request;

class ResumeMedisRajalController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pasien' => 'required',
            'medical_record_number' => 'required',
            'tgl_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'tgl_masuk' => 'required',
            'cara_keluar' => 'required',
            'berat_lahir' => 'nullable',
            'anamnesa' => 'required',
            'diagnosa_utama' => 'required',
            'diagnosa_tambahan' => 'nullable',
            'tindakan_utama' => 'nullable',
            'tindakan_tambahan' => 'nullable',
            'is_ttd' => 'required',
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
}
