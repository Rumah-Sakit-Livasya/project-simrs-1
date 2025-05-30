<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AssesmentGadarController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id'     => 'required|exists:registrations,id',
            'user_id'             => 'required|exists:users,id',
            'tgl_masuk'           => 'required|date',
            'tgl_dilayani'        => 'required|date',
            'jam_masuk'           => 'required',
            'jam_dilayani'        => 'required',
            'kesadaran'           => 'nullable|string',
            'gcs_e'               => 'nullable|integer',
            'gcs_v'               => 'nullable|integer',
            'gcs_m'               => 'nullable|integer',
            'tekanan_darah'       => 'nullable|string',
            'nadi'                => 'nullable|string',
            'pernapasan'          => 'nullable|string',
            'spo2'                => 'nullable|string',
            'suhu'                => 'nullable|string',
            'skala_nyeri'         => 'nullable|string',
            'nyeri_provokatif'      => 'nullable|string',
            'nyeri_quality'     => 'nullable|string',
            'nyeri_region'        => 'nullable|string',
            'nyeri_time'        => 'nullable|string',
            'nyeri'               => 'nullable|string',
            'nyeri_hilang_apabila'          => 'nullable|string',
            'nutrisi'             => 'nullable|string',
            'riwayat_penyakit'    => 'nullable|string',
            'alergi'              => 'nullable|string',
            'alergi_keterangan'   => 'nullable|string',
            'keluhan_utama'       => 'nullable|string',
            'riwayat_pengobatan'  => 'nullable|string',
            'riwayat_penyakit_keluarga' => 'nullable|string',
            'diagnosa_keperawatan_1' => 'nullable|string',
            'rencana_tindak_lanjut_1' => 'nullable|string',
            'diagnosa_keperawatan_2' => 'nullable|string',
            'rencana_tindak_lanjut_2' => 'nullable|string',
            'diagnosa_keperawatan_3' => 'nullable|string',
            'rencana_tindak_lanjut_3' => 'nullable|string',
            'kasus_trauma' => 'nullable|boolean',
            'kasus_non_trauma' => 'nullable|boolean',
            'kasus_obstetri' => 'nullable|boolean',
            'kasus_rujukan' => 'nullable|boolean',
            'kasus_tanda_kedukaan' => 'nullable|boolean',
            'kasus_apneu' => 'nullable|boolean',
            'transportasi_igd' => 'nullable|string',
            'spesialistik' => 'nullable|string',
            'hambatan_tidak_ada' => 'nullable|boolean',
            'hambatan_bahasa' => 'nullable|boolean',
            'hambatan_fisik' => 'nullable|boolean',
            'hambatan_tuli' => 'nullable|boolean',
            'hambatan_bisu' => 'nullable|boolean',
            'hambatan_buta' => 'nullable|boolean',
            'pra_tinggi_badan' => 'nullable|string',
            'pra_berat_badan' => 'nullable|string',
            'pra_gcs' => 'nullable|string',
            'pra_tekanan_darah' => 'nullable|string',
            'pra_nadi' => 'nullable|string',
            'pra_suhu' => 'nullable|string',
            'pra_rr' => 'nullable|string',
            'pra_sp02' => 'nullable|string',
            'pra_o2' => 'nullable|string',
            'pra_data_penunjang' => 'nullable|string',
            'pra_obat_infus' => 'nullable|string',
            'pra_alasan_dirujuk' => 'nullable|string',
            'pra_lain_lain' => 'nullable|string',
            'status_psikologis' => 'nullable|string',
            'hubungan_keluarga' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'pendidikan' => 'nullable|string',
            'status_mental' => 'nullable|string',
            'tempat_tinggal' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'masalah_perilaku' => 'nullable|string',
            'kerabat_dihubungi' => 'nullable|string',
            'agama' => 'nullable|string',
            'kekerasan' => 'nullable|string',
            'kontak_kerabat' => 'nullable|string',
            'penghasilan' => 'nullable|string',
            'flacc_wajah' => 'nullable|string',
            'flacc_kaki' => 'nullable|string',
            'flacc_aktivitas' => 'nullable|string',
            'flacc_menangis' => 'nullable|string',
            'flacc_bersuara' => 'nullable|string',
            'flacc_skor' => 'nullable|string',
            'keadaan_umum' => 'nullable|string',
            'berjalan_stabil' => 'nullable|boolean',
            'alat_bantu' => 'nullable|boolean',
            'pegang_meja' => 'nullable|boolean',
            'gizi_penurunan_bb' => 'nullable|string',
            'gizi_asupan_makanan' => 'nullable|string',
            'gizi_kondisi_anak' => 'nullable|boolean',
            'gizi_kondisi_lansia' => 'nullable|boolean',
            'gizi_kondisi_komplikasi' => 'nullable|boolean',
            'gizi_kondisi_kanker' => 'nullable|boolean',
            'gizi_kondisi_hiv' => 'nullable|boolean',
            'gizi_kondisi_tb' => 'nullable|boolean',
            'gizi_kondisi_bedah' => 'nullable|boolean',
            'gizi_kondisi_luka' => 'nullable|boolean',
            'barthel_makan' => 'nullable|string',
            'barthel_mandi' => 'nullable|string',
            'barthel_berhias' => 'nullable|string',
            'barthel_berpakaian' => 'nullable|string',
            'barthel_bab' => 'nullable|string',
            'barthel_bak' => 'nullable|string',
            'barthel_toileting' => 'nullable|string',
            'barthel_transfer' => 'nullable|string',
            'barthel_mobilitas' => 'nullable|string',
            'barthel_naik_tangga' => 'nullable|string',
            'barthel_skor' => 'nullable|string',
            'barthel_analisa' => 'nullable|string',
            'discharge_kondisi_umur65' => 'nullable|boolean',
            'discharge_kondisi_mobilitas' => 'nullable|boolean',
            'discharge_perawatan_lanjutan' => 'nullable|boolean',
            'discharge_bantuan_aktivitas' => 'nullable|boolean',
            'discharge_perawatan_diri' => 'nullable|boolean',
            'discharge_pemberian_obat' => 'nullable|boolean',
            'discharge_pemantauan_diet' => 'nullable|boolean',
            'discharge_perawatan_luka' => 'nullable|boolean',
            'discharge_latihan_fisik' => 'nullable|boolean',
            'discharge_pendamping_tenaga' => 'nullable|boolean',
            'discharge_bantuan_medis' => 'nullable|boolean',
            'discharge_bantuan_aktivitas_fisik' => 'nullable|boolean',
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

            $gadar = AssesmentKeperawatanGadar::updateOrCreate($search, $dataToSave);

            // SIGNATURE
            if ($request->filled('signature_image')) {
                $imageData = $request->input('signature_image');
                $pic = $request->input('pic');
                $pic = $request->input('role');
                $image = str_replace('data:image/png;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image);
                $imageName = 'ttd_' . time() . '.png';
                $path = 'signatures/' . $imageName;

                // Cek apakah sudah ada tanda tangan lama
                $existingSignature = $gadar->signature;

                if ($existingSignature && \Storage::disk('public')->exists($existingSignature->signature)) {
                    \Storage::disk('public')->delete($existingSignature->signature);
                }

                // Simpan file baru ke storage
                \Storage::disk('public')->put($path, base64_decode($image));

                // Simpan ke tabel `signatures` via relasi
                $gadar->signature()->updateOrCreate(
                    [
                        'signable_id' => $gadar->id,
                        'signable_type' => get_class($gadar),
                    ],
                    [
                        'signature' => $path,
                        'pic' => $pic,
                    ]
                );
            }

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
