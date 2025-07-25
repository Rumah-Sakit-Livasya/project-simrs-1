<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class TriageController extends Controller
{
    public function get($id)
    {
        $registration = Registration::find($id);
        $triage = Triage::where('registration_id', $registration->id)->first();

        if (!$triage) {
            return response()->json([
                'message' => 'Data Triage tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data Triage ditemukan.',
            'data' => $triage
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'jam_masuk' => 'required',
            'jam_dilayani' => 'required',
            'pr' => 'nullable|integer',
            'bp' => 'nullable|string',
            'signature_image' => 'nullable|string', // base64 PNG
        ]);

        $triage = \App\Models\SIMRS\Pelayanan\Triage::updateOrCreate(
            ['registration_id' => $request->registration_id],
            [
                'tgl_masuk' => $request->tgl_masuk,
                'jam_masuk' => $request->jam_masuk,
                'jam_dilayani' => $request->jam_dilayani,
                'pr' => $request->pr,
                'bp' => $request->bp,
                'body_height' => $request->body_height,
                'bmi' => $request->bmi,
                'lingkar_dada' => $request->lingkar_dada,
                'sp02' => $request->sp02,
                'rr' => $request->rr,
                'temperatur' => $request->temperatur,
                'body_weight' => $request->body_weight,
                'kat_bmi' => $request->kat_bmi,
                'lingkar_perut' => $request->lingkar_perut,
                'auto_anamnesa' => $request->has('auto_anamnesa'),
                'allo_anamnesa' => $request->has('allo_anamnesa'),
                'airway_merah' => json_encode($request->airway_merah),
                'airway_kuning' => json_encode($request->airway_kuning),
                'airway_hijau' => json_encode($request->airway_hijau),
                'breathing_merah' => json_encode($request->breathing_merah),
                'breathing_kuning' => json_encode($request->breathing_kuning),
                'breathing_hijau' => json_encode($request->breathing_hijau),
                'circulation_merah' => json_encode($request->circulation_merah),
                'circulation_kuning' => json_encode($request->circulation_kuning),
                'circulation_hijau' => json_encode($request->circulation_hijau),
                'disability' => json_encode($request->disability),
                'kesimpulan' => json_encode($request->kesimpulan),
                'daa_hitam' => $request->has('daa_hitam')
            ]
        );

        // SIGNATURE
        if ($request->filled('signature_image')) {
            $imageData = $request->input('signature_image');
            $pic = $request->input('pic');
            $role = $request->input('role') ?? "pic";
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'ttd_' . time() . '.png';
            $path = 'signatures/' . $imageName;

            // Cek apakah sudah ada tanda tangan lama
            $existingSignature = $triage->signature;

            if ($existingSignature && \Storage::disk('public')->exists($existingSignature->signature)) {
                \Storage::disk('public')->delete($existingSignature->signature);
            }

            // Simpan file baru ke storage
            \Storage::disk('public')->put($path, base64_decode($image));

            // Simpan ke tabel `signatures` via relasi
            $triage->signature()->updateOrCreate(
                [
                    'signable_id' => $triage->id,
                    'signable_type' => get_class($triage),
                ],
                [
                    'signature' => $path,
                    'pic' => $pic,
                    'role' => $role,
                ]
            );
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $triage
        ], 201);
    }
}
