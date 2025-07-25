<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\EWSObstetri;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class EWSObstetriController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'user_id' => 'required|exists:users,id',
            'tgl' => 'required|date',
            'jam' => 'required',
            'laju_respirasi' => 'required|string',
            'saturasi' => 'required|string',
            'suplemen' => 'nullable|string',
            'temperatur' => 'required|string',
            'tekanan_darah_sistolik' => 'required|string',
            'tekanan_darah_diastole' => 'required|string',
            'laju_jantung' => 'required|string',
            'kesadaran' => 'required|string',
            'discharge' => 'required|string',
            'proteinuria' => 'required|string',
            'skor_total' => 'required|integer',
            'gds' => 'nullable|string',
            'skor_nyeri' => 'nullable|string',
            'urin_output' => 'nullable|string',
        ]);

        try {
            // Cari data berdasarkan registration_id dan user_id, update atau buat baru
            $searchData = [
                'registration_id' => $validatedData['registration_id'],
                'user_id' => $validatedData['user_id'],
            ];
            $dataToSave = [
                'tgl' => $validatedData['tgl'],
                'jam' => $validatedData['jam'],
                'laju_respirasi' => $validatedData['laju_respirasi'],
                'saturasi' => $validatedData['saturasi'],
                'suplemen' => $validatedData['suplemen'] ?? null,
                'temperatur' => $validatedData['temperatur'],
                'tekanan_darah_sistolik' => $validatedData['tekanan_darah_sistolik'],
                'tekanan_darah_diastole' => $validatedData['tekanan_darah_diastole'],
                'laju_jantung' => $validatedData['laju_jantung'],
                'kesadaran' => $validatedData['kesadaran'],
                'discharge' => $validatedData['discharge'],
                'proteinuria' => $validatedData['proteinuria'],
                'skor_total' => $validatedData['skor_total'],
                'gds' => $validatedData['gds'] ?? null,
                'skor_nyeri' => $validatedData['skor_nyeri'] ?? null,
                'urin_output' => $validatedData['urin_output'] ?? null,
            ];
            EWSObstetri::updateOrCreate($searchData, $dataToSave);
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getData($id)
    {
        $registration = Registration::find($id);
        $ewsObstetri = EWSObstetri::where('registration_id', $registration->id)->first();

        if (!$ewsObstetri) {
            return response()->json([
                'message' => 'Data EWS Obstetri tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data EWS Obstetri ditemukan.',
            'data' => $ewsObstetri
        ], 200);
    }
}
