<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\AssesmentGadar;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AssesmentGadarController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id'     => 'required|exists:registrations,id',
            'user_id'     => 'required|exists:users,id',
            'tgl'                 => 'required|date',
            'jam'                 => 'required|date_format:H:i:s',
            'kesadaran'           => 'nullable|string',
            'gcs_e'               => 'nullable|integer',
            'gcs_v'               => 'nullable|integer',
            'gcs_m'               => 'nullable|integer',
            'tekanan_darah'       => 'nullable|string',
            'nadi'                => 'nullable|string',
            'pernapasan'          => 'nullable|string',
            'spo2'                => 'nullable|string',
            'suhu'                => 'nullable|string',
            'nyeri'               => 'nullable|string',
            'nyeri_lokasi'        => 'nullable|string',
            'nyeri_karakter'      => 'nullable|string',
            'nyeri_durasi'        => 'nullable|string',
            'nyeri_frekuensi'     => 'nullable|string',
            'nyeri_skor'          => 'nullable|string',
            'skala_nyeri'         => 'nullable|string',
            'nutrisi'             => 'nullable|string',
            'riwayat_penyakit'    => 'nullable|string',
            'alergi'              => 'nullable|string',
            'alergi_keterangan'   => 'nullable|string',
        ]);

        try {
            $userId = $validatedData['user_id'];

            // Gunakan registration_id sebagai kunci update
            $search = [
                'registration_id' => $validatedData['registration_id']
            ];

            $dataToSave = array_merge($validatedData, [
                'user_id' => $userId
            ]);

            AssesmentKeperawatanGadar::updateOrCreate($search, $dataToSave);

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

        if (!$registration) {
            return response()->json(['message' => 'Registration tidak ditemukan.'], 404);
        }

        $data = AssesmentKeperawatanGadar::where('registration_id', $registration->id)->first();

        if (!$data) {
            return response()->json(['message' => 'Data assesment tidak ditemukan.'], 404);
        }

        return response()->json([
            'message' => 'Data ditemukan.',
            'data' => $data
        ], 200);
    }
}
