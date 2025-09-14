<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\PengkajianDokterIGD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengkajianDokterIgdController extends Controller
{
    /**
     * Store a newly created or updated resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi Awal
        $validator = Validator::make($request->all(), [
            'registration_id' => 'required|exists:registrations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Proses Data Kompleks (Status Generalis & Pemeriksaan Penunjang)

        // Proses Status Generalis
        $statusGeneralisData = [];
        if ($request->has('generalis_check')) {
            foreach ($request->generalis_check as $key => $value) {
                // Hanya simpan item yang dicentang dan memiliki teks
                if (isset($request->generalis_text[$key])) {
                    $statusGeneralisData[$key] = $request->generalis_text[$key];
                }
            }
        }

        // Proses Pemeriksaan Penunjang
        $pemeriksaanPenunjangData = [];
        $penunjangKeys = [
            'laboratorium',
            'ekg',
            'radiologi',
            'pemeriksaan_lainnya',
            'rapid_antigen',
            'rapid_antibody'
        ];
        foreach ($penunjangKeys as $key) {
            $pemeriksaanPenunjangData[$key] = [
                'checked' => $request->has("penunjang_check.{$key}"),
                'text' => $key === 'pemeriksaan_lainnya'
                    ? $request->input('pemeriksaan_lainnya_text', $request->input("penunjang_text.{$key}", null))
                    : $request->input("penunjang_text.{$key}", null)
            ];
        }

        // 3. Siapkan Semua Data untuk Disimpan
        $dataToSave = $request->except([
            '_token',
            '_method',
            'action_type',
            'generalis_check',
            'generalis_text',
            'penunjang_check',
            'penunjang_text',
            'pemeriksaan_lainnya_text'
        ]);

        // Masukkan data JSON yang sudah diproses
        $dataToSave['status_generalis'] = json_encode($statusGeneralisData);
        $dataToSave['pemeriksaan_penunjang'] = json_encode($pemeriksaanPenunjangData);

        // Handle checkbox tunggal (jika tidak dicentang, tidak akan dikirim)
        $dataToSave['edukasi_tidak_dapat_diberikan'] = $request->has('edukasi_tidak_dapat_diberikan');

        // Pastikan field-field vital sesuai inputan di pengkajian-dokter-igd.blade.php
        // Tanda vital utama
        $dataToSave['td'] = $request->input('td', $request->input('bp'));
        $dataToSave['pr_triage'] = $request->input('pr_triage', $request->input('pr'));
        $dataToSave['rr_triage'] = $request->input('rr_triage', $request->input('rr'));
        $dataToSave['sb'] = $request->input('sb', $request->input('temperatur'));
        $dataToSave['dokterSPO2'] = $request->input('dokterSPO2', $request->input('sp02'));

        // Tanda vital saat pulang
        $dataToSave['td_pulang'] = $request->input('td_pulang');
        $dataToSave['nadi_pulang'] = $request->input('nadi_pulang');
        $dataToSave['rr_pulang'] = $request->input('rr_pulang');
        $dataToSave['suhu_pulang'] = $request->input('suhu_pulang');
        $dataToSave['spo2_pulang'] = $request->input('spo2_pulang');

        // Status Generalis dan Lokalis
        $dataToSave['status_lokalis'] = $request->input('status_lokalis');

        // Diagnosa dan Terapi
        $dataToSave['diagnosa_kerja'] = $request->input('diagnosa_kerja');
        $dataToSave['diagnosa_banding'] = $request->input('diagnosa_banding');
        $dataToSave['terapi'] = $request->input('terapi');
        $dataToSave['tindakan'] = $request->input('tindakan');

        // Edukasi
        $dataToSave['edukasi'] = $request->input('edukasi');
        $dataToSave['edukasi_tidak_dapat_diberikan'] = $request->has('edukasi_tidak_dapat_diberikan');
        $dataToSave['alasan_edukasi_tidak_diberikan'] = $request->input('alasan_edukasi_tidak_diberikan');

        // Kondisi Pulang
        $dataToSave['kondisi_pulang'] = $request->input('kondisi_pulang');
        $dataToSave['jam_meninggal'] = $request->input('jam_meninggal');

        // 4. Gunakan updateOrCreate untuk menyimpan atau memperbarui data
        try {
            $pengkajian = PengkajianDokterIGD::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToSave
            );

            // Set audit trails (created_by dan updated_by)
            if ($pengkajian->wasRecentlyCreated) {
                $pengkajian->created_by = Auth::id();
            }
            $pengkajian->updated_by = Auth::id();
            $pengkajian->save();

            return response()->json(['message' => 'Data pengkajian dokter IGD berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan ke database
            return response()->json(['message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }
}
