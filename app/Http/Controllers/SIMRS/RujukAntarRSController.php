<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\Pelayanan\RujukAntarRS;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class RujukAntarRSController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id'           => 'required|exists:registrations,id',
            'user_id'                   => 'required|exists:users,id',
            'dokter_penerima'           => 'required',
            'alamat_pasien'             => 'required',
            'alasan_keluar'             => 'required',
            'nama_pasien'               => 'required',
            'umur_pasien'               => 'required',
            'tgl_masuk'                 => 'required|date',
            'rs_tujuan'                 => 'required',
            'nama_ts'                   => 'required',
            'nama_rs'                   => 'required',
            'pemeriksaan_laboratorium'  => 'nullable|string',
            'pemeriksaan_radiologi'     => 'nullable|string',
            'pemeriksaan_lainnya'       => 'nullable|string',
            'tindakan_dan_terapi'       => 'nullable|string',
            'diagnosa_masuk'            => 'nullable|string',
            'alasan_dirujuk'            => 'nullable|string',
            'edukasi_pasien'            => 'nullable|string',
            'dpjp'                      => 'required|string',
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

            RujukAntarRS::updateOrCreate($search, $dataToSave);

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

        $data = RujukAntarRS::where('registration_id', $registration->id)->first();

        if (!$data) {
            return response()->json(['message' => 'Data assesment tidak ditemukan.'], 404);
        }

        return response()->json([
            'message' => 'Data ditemukan.',
            'data' => $data
        ], 200);
    }
}
