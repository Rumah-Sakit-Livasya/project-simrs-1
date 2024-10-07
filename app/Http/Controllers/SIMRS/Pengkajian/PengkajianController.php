<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\PengkajianNurseRajal;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class PengkajianController extends Controller
{
    public function getPengkajianRajal($id)
    {
        try {
            $pengkajian = PengkajianNurseRajal::findOrFail($id);
            // Jika data pic disimpan sebagai JSON string, parse dulu
            return response()->json($pengkajian, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function storeOrUpdatePengkajianRajal(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'registration_id' => 'required|exists:registrations,id',
            'tgl_masuk' => 'nullable|date',
            'jam_masuk' => 'nullable',
            'tgl_dilayani' => 'nullable|date',
            'jam_dilayani' => 'nullable',
            'keluhan_utama' => 'nullable|string',
            'pr' => 'nullable|string',
            'rr' => 'nullable|string',
            'bp' => 'nullable|string',
            'temperatur' => 'nullable|string',
            'body_height' => 'nullable|string',
            'body_weight' => 'nullable|string',
            'bmi' => 'nullable|string',
            'kat_bmi' => 'nullable|string',
            'sp02' => 'nullable|string',
            'lingkar_kepala' => 'nullable|string',
            'diagnosa_keperawatan' => 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'alergi_obat' => 'nullable|string',
            'ket_alergi_obat' => 'nullable|string',
            'reaksi_alergi_obat' => 'nullable|string',
            'alergi_makanan' => 'nullable|string',
            'ket_alergi_makanan' => 'nullable|string',
            'reaksi_alergi_makanan' => 'nullable|string',
            'alergi_lainnya' => 'nullable|string',
            'ket_alergi_lainnya' => 'nullable|string',
            'reaksi_alergi_lainnya' => 'nullable|string',
            'gelang' => 'nullable|boolean',
            'skor_nyeri' => 'nullable|string',
            'provokatif' => 'nullable|string',
            'quality' => 'nullable|string',
            'region' => 'nullable|string',
            'time' => 'nullable|string',
            'nyeri' => 'nullable|string',
            'nyeri_hilang' => 'nullable|string',
            'penurunan_bb' => 'nullable|string',
            'asupan_makan' => 'nullable|string',
            'kondisi_khusus1' => 'nullable|string',
            'kondisi_khusus2' => 'nullable|string',
            'kondisi_khusus3' => 'nullable|string',
            'kondisi_khusus4' => 'nullable|string',
            'kondisi_khusus5' => 'nullable|string',
            'kondisi_khusus6' => 'nullable|string',
            'kondisi_khusus7' => 'nullable|string',
            'kondisi_khusus8' => 'nullable|string',
            'imunisasi_dasar1' => 'nullable|string',
            'imunisasi_dasar2' => 'nullable|string',
            'imunisasi_dasar3' => 'nullable|string',
            'imunisasi_dasar4' => 'nullable|string',
            'imunisasi_dasar5' => 'nullable|string',
            'resiko_jatuh1' => 'nullable|string',
            'resiko_jatuh2' => 'nullable|string',
            'resiko_jatuh3' => 'nullable|string',
        ]);

        // Check if the registration type is 'rawat-jalan'
        $registration = Registration::find($validatedData['registration_id']);
        if ($registration->registration_type != 'rawat-jalan') {
            return response()->json(['error' => 'Registration type must be rawat-jalan.'], 400);
        }

        // Check if a PengkajianNurseRajal already exists for this registration
        $existingPengkajian = $registration->pengkajian_nurse_rajal;

        try {
            if ($existingPengkajian) {
                // Update the existing PengkajianNurseRajal record
                $existingPengkajian->update($validatedData);
                return response()->json(['message' => 'Data updated successfully!', 'data' => $existingPengkajian]);
            } else {
                // Create a new PengkajianNurseRajal record
                $pengkajian = PengkajianNurseRajal::create($validatedData);
                return response()->json(['message' => 'Data saved successfully!', 'data' => $pengkajian], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
