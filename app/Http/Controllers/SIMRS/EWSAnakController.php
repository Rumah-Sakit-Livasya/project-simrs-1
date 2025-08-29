<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\EWSAnak;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class EWSAnakController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'user_id' => 'required|exists:users,id',
            'tgl' => 'required|date',
            'jam' => 'required',
            'keadaan_umum' => 'required|string',
            'kardio_vaskular' => 'required|string',
            'respirasi' => 'required|string',
            'skor_total' => 'required|integer',
        ]);

        try {
            // Data yang digunakan untuk pencarian
            $searchData = [
                'registration_id' => $validatedData['registration_id'],
            ];

            // Data yang akan diperbarui atau dibuat
            $dataToSave = [
                'registration_id' => $validatedData['registration_id'],
                'user_id' => $validatedData['user_id'],
                'tgl' => $validatedData['tgl'],
                'jam' => $validatedData['jam'],
                'keadaan_umum' => $validatedData['keadaan_umum'],
                'kardio_vaskular' => $validatedData['kardio_vaskular'],
                'respirasi' => $validatedData['respirasi'],
                'skor_total' => $validatedData['skor_total'],
            ];

            // Simpan data ke database
            EWSAnak::updateOrCreate($searchData, $dataToSave);
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (QueryException $e) {
            // Tangani kesalahan saat menyimpan data
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getData($id)
    {
        $registration = Registration::find($id);
        $ewsAnak = EWSAnak::where('registration_id', $registration->id)->first();

        if (!$ewsAnak) {
            return response()->json([
                'message' => 'Data EWS Anak tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data EWS Anak ditemukan.',
            'data' => $ewsAnak
        ], 200);
    }
}
