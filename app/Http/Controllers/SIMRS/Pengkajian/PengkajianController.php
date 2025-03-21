<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use Illuminate\Http\Request;

class PengkajianController extends Controller
{
    public function getPengkajianRajal(Request $request, $type, $registration_number)
    {
        try {
            $registration = Registration::where('registration_number', $registration_number)->where('registration_type', $type)->first();
            $pengkajian = $registration->pengkajian_nurse_rajal;
            if ($pengkajian) {
                return response()->json($pengkajian, 200);
            } else {

                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTransferPasienAntarRuangan($id)
    {
        try {
            $transfer = TransferPasienAntarRuangan::findOrFail($id);
            // Jika data pic disimpan sebagai JSON string, parse dulu
            return response()->json($transfer, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function storeOrUpdatePengkajianRajal(Request $request)
    {
        // dd($request);
        // Validate the incoming request data
        // $validatedData = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'registration_id' => 'required|exists:registrations,id',
        //     'tgl_masuk' => 'nullable|date',
        //     'jam_masuk' => 'nullable',
        //     'tgl_dilayani' => 'nullable|date',
        //     'jam_dilayani' => 'nullable',
        //     'keluhan_utama' => 'nullable|string',
        //     'pr' => 'nullable|string',
        //     'rr' => 'nullable|string',
        //     'bp' => 'nullable|string',
        //     'temperatur' => 'nullable|string',
        //     'body_height' => 'nullable|string',
        //     'body_weight' => 'nullable|string',
        //     'bmi' => 'nullable|string',
        //     'kat_bmi' => 'nullable|string',
        //     'sp02' => 'nullable|string',
        //     'lingkar_kepala' => 'nullable|string',
        //     'diagnosa_keperawatan' => 'nullable|string',
        //     'rencana_tindak_lanjut' => 'nullable|string',
        //     'alergi_obat' => 'nullable|string',
        //     'ket_alergi_obat' => 'nullable|string',
        //     'reaksi_alergi_obat' => 'nullable|string',
        //     'alergi_makanan' => 'nullable|string',
        //     'ket_alergi_makanan' => 'nullable|string',
        //     'reaksi_alergi_makanan' => 'nullable|string',
        //     'alergi_lainnya' => 'nullable|string',
        //     'ket_alergi_lainnya' => 'nullable|string',
        //     'reaksi_alergi_lainnya' => 'nullable|string',
        //     'gelang' => 'nullable|boolean',
        //     'skor_nyeri' => 'nullable|string',
        //     'provokatif' => 'nullable|string',
        //     'quality' => 'nullable|string',
        //     'region' => 'nullable|string',
        //     'time' => 'nullable|string',
        //     'nyeri' => 'nullable|string',
        //     'nyeri_hilang' => 'nullable|string',
        //     'penurunan_bb' => 'nullable|string',
        //     'asupan_makan' => 'nullable|string',
        //     'kondisi_khusus1' => 'nullable|string',
        //     'kondisi_khusus2' => 'nullable|string',
        //     'kondisi_khusus3' => 'nullable|string',
        //     'kondisi_khusus4' => 'nullable|string',
        //     'kondisi_khusus5' => 'nullable|string',
        //     'kondisi_khusus6' => 'nullable|string',
        //     'kondisi_khusus7' => 'nullable|string',
        //     'kondisi_khusus8' => 'nullable|string',
        //     'imunisasi_dasar1' => 'nullable|string',
        //     'imunisasi_dasar2' => 'nullable|string',
        //     'imunisasi_dasar3' => 'nullable|string',
        //     'imunisasi_dasar4' => 'nullable|string',
        //     'imunisasi_dasar5' => 'nullable|string',
        //     'resiko_jatuh1' => 'nullable|string',
        //     'resiko_jatuh2' => 'nullable|string',
        //     'resiko_jatuh3' => 'nullable|string',

        //     //======== RIWAYAT PSIKOSOSIAL, SPIRITUAL & KEPERCAYAAN =========
        //     'status_psikologis' => 'nullable|string',
        //     'status_spiritual' => 'nullable|string',
        //     'masalah_prilaku' => 'nullable|string',
        //     'hub_dengan_keluarga' => 'nullable|string',
        //     'tempat_tinggal' => 'nullable|string',
        //     'kerabat_dihub' => 'nullable|string',
        //     'no_kontak_kerabat' => 'nullable|string',
        //     'status_perkawinan' => 'nullable|string',
        //     'pekerjaan' => 'nullable|string',
        //     'penghasilan' => 'nullable|string',
        //     'pendidikan' => 'nullable|string',

        //     //======== KEBUTUHAN EDUKASI =========
        //     'hambatan_belajar1' => 'nullable|string',
        //     'hambatan_belajar2' => 'nullable|string',
        //     'hambatan_belajar3' => 'nullable|string',
        //     'hambatan_belajar4' => 'nullable|string',
        //     'hambatan_belajar5' => 'nullable|string',
        //     'hambatan_belajar6' => 'nullable|string',
        //     'hambatan_belajar7' => 'nullable|string',
        //     'hambatan_belajar8' => 'nullable|string',
        //     'hambatan_belajar9' => 'nullable|string',
        //     'hambatan_lainnya' => 'nullable|string',
        //     'kebutuhan_penerjemah' => 'nullable|string',
        //     'kebuthan_pembelajaran1' => 'nullable|string',
        //     'kebuthan_pembelajaran2' => 'nullable|string',
        //     'kebuthan_pembelajaran3' => 'nullable|string',
        //     'kebuthan_pembelajaran4' => 'nullable|string',
        //     'kebuthan_pembelajaran5' => 'nullable|string',
        //     'kebuthan_pembelajaran6' => 'nullable|string',
        //     'kebuthan_pembelajaran7' => 'nullable|string',
        //     'pembelajaran_lainnya' => 'nullable|string',
        //     'kebuthan_pembelajaran1' => 'nullable|string',

        //     //======== Assesment fungsional =========
        //     'sensorik_penglihatan' => 'nullable|string',
        //     'sensorik_penciuman' => 'nullable|string',
        //     'sensorik_pendengaran' => 'nullable|string',

        //     //======== Kognitif =========
        //     'kognitif' => 'nullable|string',

        //     //======== Motorik =========
        //     'motorik_aktifitas' => 'nullable|string',
        //     'motorik_berjalan' => 'nullable|string',
        // ]);
        // // Daftar semua checkbox yang diharapkan
        // $checkboxes = [
        //     'hambatan_belajar1',
        //     'hambatan_belajar2',
        //     'hambatan_belajar3',
        //     'hambatan_belajar4',
        //     'hambatan_belajar5',
        //     'hambatan_belajar6',
        //     'hambatan_belajar7',
        //     'hambatan_belajar8',
        //     'hambatan_belajar9',
        //     'kebuthan_pembelajaran1',
        //     'kebuthan_pembelajaran2',
        //     'kebuthan_pembelajaran3',
        //     'kebuthan_pembelajaran4',
        //     'kebuthan_pembelajaran5',
        //     'kebuthan_pembelajaran6',
        //     'kebuthan_pembelajaran7',
        //     'kondisi_khusus1',
        //     'kondisi_khusus2',
        //     'kondisi_khusus3',
        //     'kondisi_khusus4',
        //     'kondisi_khusus5',
        //     'kondisi_khusus6',
        //     'kondisi_khusus7',
        //     'kondisi_khusus8',
        //     'imunisasi_dasar1',
        //     'imunisasi_dasar2',
        //     'imunisasi_dasar3',
        //     'imunisasi_dasar4',
        //     'imunisasi_dasar5',
        //     'resiko_jatuh1',
        //     'resiko_jatuh2',
        //     'resiko_jatuh3',
        // ];

        // // Mengatur nilai ke null jika tidak ada request
        // foreach ($checkboxes as $key) {
        //     $validatedData[$key] = $validatedData[$key] ?? null; // Jika tidak ada nilai, set ke null
        // }

        // Check if the registration type is 'rawat-jalan'
        $registration = Registration::find($request->registration_id);

        if (!$registration) {
            return response()->json(['error' => 'Registration not found.'], 404);
        }

        if ($registration->registration_type != 'rawat-jalan') {
            return response()->json(['error' => 'Registration type must be rawat-jalan.'], 400);
        }

        // Cek apakah sudah ada data PengkajianNurseRajal
        $existingPengkajian = $registration->pengkajian_nurse_rajal;

        // Konversi array menjadi JSON
        $sensorik = json_encode([
            'sensorik_penglihatan' => $request->sensorik_penglihatan,
            'sensorik_penciuman' => $request->sensorik_penciuman,
            'sensorik_pendengaran' => $request->sensorik_pendengaran,
        ]);

        $motorik = json_encode([
            'motorik_aktifitas' => $request->motorik_aktifitas,
            'motorik_berjalan' => $request->motorik_berjalan,
        ]);

        // Data yang akan disimpan
        $data = [
            'tgl_masuk' => $request->tgl_masuk,
            'jam_masuk' => $request->jam_masuk,
            'tgl_dilayani' => $request->tgl_dilayani,
            'jam_dilayani' => $request->jam_dilayani,
            'keluhan_utama' => $request->keluhan_utama,
            'pr' => $request->pr,
            'rr' => $request->rr,
            'bp' => $request->bp,
            'temperatur' => $request->temperatur,
            'body_height' => $request->body_height,
            'body_weight' => $request->body_weight,
            'bmi' => $request->bmi,
            'kat_bmi' => $request->kat_bmi,
            'sp02' => $request->sp02,
            'lingkar_kepala' => $request->lingkar_kepala,
            'diagnosa_keperawatan' => $request->diagnosa_keperawatan,
            'rencana_tindak_lanjut' => $request->rencana_tindak_lanjut,
            'alergi_obat' => $request->alergi_obat,
            'ket_alergi_obat' => $request->ket_alergi_obat,
            'ket_alergi_makanan' => $request->ket_alergi_makanan,
            'alergi_makanan' => $request->alergi_makanan,
            'ket_alergi_lainnya' => $request->ket_alergi_lainnya,
            'alergi_lainnya' => $request->alergi_lainnya,
            'reaksi_alergi_obat' => $request->reaksi_alergi_obat,
            'reaksi_alergi_makanan' => $request->reaksi_alergi_makanan,
            'reaksi_alergi_lainnya' => $request->reaksi_alergi_lainnya,
            'gelang' => $request->gelang,
            'skor_nyeri' => $request->skor_nyeri,
            'provokatif' => $request->provokatif,
            'quality' => $request->quality,
            'region' => $request->region,
            'time' => $request->time,
            'nyeri' => $request->nyeri,
            'nyeri_hilang' => $request->nyeri_hilang,
            'penurunan_bb' => $request->penurunan_bb,
            'asupan_makan' => $request->asupan_makan,
            'kondisi_khusus' => json_encode($request->kondisi_khusus),
            'imunisasi_dasar' => json_encode($request->imunisasi_dasar),
            'hasil_resiko_jatuh' => $request->hasil_resiko_jatuh,
            'status_psikologis' => $request->status_psikologis,
            'status_spiritual' => $request->status_spiritual,
            'masalah_prilaku' => $request->masalah_prilaku,
            'kekerasan_dialami' => $request->kekerasan_dialami,
            'hub_dengan_keluarga' => $request->hub_dengan_keluarga,
            'tempat_tinggal' => $request->tempat_tinggal,
            'kerabat_dihub' => $request->kerabat_dihub,
            'no_kontak_kerabat' => $request->no_kontak_kerabat,
            'penghasilan' => $request->penghasilan,
            'hambatan_belajar' => json_encode($request->hambatan_belajar),
            'hambatan_lainnya' => $request->hambatan_lainnya,
            'kebutuhan_penerjemah' => $request->kebutuhan_penerjemah,
            'kebutuhan_pembelajaran' => json_encode($request->kebutuhan_pembelajaran), // Format JSON
            'pembelajaran_lainnya' => $request->pembelajaran_lainnya,
            'kognitif' => $request->kognitif,
            'registration_id' => $request->registration_id,
            'user_id' => $request->user_id,
            'sensorik' => $sensorik, // Sudah dalam format JSON
            'motorik' => $motorik, // Sudah dalam format JSON
            'updated_at' => now(),
        ];

        try {
            if ($existingPengkajian) {
                // Update data yang sudah ada
                
                $data['modified_by'] = $request->user_id;
                $existingPengkajian->update($data);
                return response()->json(['message' => 'Data updated successfully!', 'data' => $existingPengkajian]);
            } else {
                // Buat data baru
                $data['created_by'] = $request->user_id;
                $pengkajian = PengkajianNurseRajal::create($data);
                return response()->json(['message' => 'Data saved successfully!', 'data' => $pengkajian], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function storeOrUpdateTransferPasienAntarRuangan(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'registration_id' => 'required|exists:registrations,id',
            'tgl' => 'nullable|string',
            'jam' => 'nullable|string',
            'tgl_masuk_pasien' => 'nullable|string',
            'jam_masuk_pasien' => 'nullable|string',
            'asesmen' => 'nullable|string',
            'masalah_keperawatan' => 'nullable|string',
            'dokter' => 'nullable|string',
            'dokter2' => 'nullable|string',
            'dokter3' => 'nullable|string',
            'ruangan_asal' => 'nullable|string',
            'kelas_asal' => 'nullable|string',
            'ruangan_pindah' => 'nullable|string',
            'kelas_pindah' => 'nullable|string',
            'tiba_diruangan' => 'nullable|string',
            'keluhan_utama' => 'nullable|string',
            'kondisi_pasien' => 'nullable|string',
            'keadaan_umum' => 'nullable|string',
            'keadaan_umum_gcs' => 'nullable|string',
            'ket_gcs' => 'nullable|string',
            'td' => 'nullable|string',
            'nd' => 'nullable|string',
            'rr' => 'nullable|string',
            'sb' => 'nullable|string',
            'bb' => 'nullable|string',
            'tb' => 'nullable|string',
            'spo2' => 'nullable|string',
            'status_nyeri' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'ket_lainnya' => 'nullable|string',
            'app_lainnya' => 'nullable|string',
            'app_lainnya_text' => 'nullable|string',
            'kesadaran' => 'nullable|string',
            'mpp' => 'nullable|string',
            'rj' => 'nullable|string',
            'kti' => 'nullable|string',
            'mpi' => 'nullable|boolean',
            'ap' => 'nullable|boolean',
            'ap_nama' => 'nullable|string',
            'ap_hubungan' => 'nullable|string',
            'alasan_pdh_temuan_anamesis' => 'nullable|string',
            'sfp' => 'nullable|string',
            'pmp_kuro' => 'nullable|string',
            'pmp_text' => 'nullable|string',
            'pmp_cateter_urine' => 'nullable|string',
            'pmp_ngt' => 'nullable|string',
            'pemeriksaan_penunjang' => 'nullable|string',
            'intervensi_tindakan' => 'nullable|string',
            'diet' => 'nullable|string',
            'ptsp_infus' => 'nullable|string',
            'ptsp_infus_text' => 'nullable|string',
            'ptsp_infus_tetesan' => 'nullable|string',
            'resep1' => 'nullable|string',
            'jam_pemberian1' => 'nullable|string',
            'resep2' => 'nullable|string',
            'jam_pemberian2' => 'nullable|string',
            'resep3' => 'nullable|string',
            'jam_pemberian3' => 'nullable|string',
            'resep4' => 'nullable|string',
            'jam_pemberian4' => 'nullable|string',
            'resep5' => 'nullable|string',
            'jam_pemberian5' => 'nullable|string',
            'resep6' => 'nullable|string',
            'jam_pemberian6' => 'nullable|string',
            'resep7' => 'nullable|string',
            'jam_pemberian7' => 'nullable|string',
            'resep8' => 'nullable|string',
            'jam_pemberian8' => 'nullable|string',
            'resep9' => 'nullable|string',
            'jam_pemberian9' => 'nullable|string',
            'resep10' => 'nullable|string',
            'jam_pemberian10' => 'nullable|string',
            'data_ttd1' => 'nullable|string',
            'nama_perawat_pengirim' => 'nullable|string',
            'data_ttd2' => 'nullable|string',
            'nama_perawat_penerima' => 'nullable|string',
            'pasien_kelmbali' => 'nullable|string',
            'keadaan_umum_after' => 'nullable|string',
            'td_after' => 'nullable |string',
            'nd_after' => 'nullable|string',
            'rr_after' => 'nullable|string',
            'sb_after' => 'nullable|string',
            'rj_after' => 'nullable|string',
            'diet_after' => 'nullable|string',
            'data_ttd3' => 'nullable|string',
            'nama_perawat_pengirim_after' => 'nullable|string',
            'data_ttd4' => 'nullable|string',
            'nama_perawat_penerima_after' => 'nullable|string',
        ]);

        // Check if the registration type is 'rawat-jalan'
        $registration = Registration::find($validatedData['registration_id']);
        // if ($registration->registration_type != 'rawat-jalan') {
        //     return response()->json(['error' => 'Registration type must be rawat-jalan.'], 400);
        // }

        // Check if a TransferPasienAntarRuangan already exists for this registration
        $existingTransfer = $registration->transfer_pasien_antar_ruangan;

        try {
            if ($existingTransfer) {
                // Update the existing TransferPasienAntarRuangan record
                $existingTransfer->update($validatedData);
                return response()->json(['message' => 'Data updated successfully!', 'data' => $existingTransfer]);
            } else {
                // Create a new TransferPasienAntarRuangan record
                $transfer = TransferPasienAntarRuangan::create($validatedData);
                return response()->json(['message' => 'Data saved successfully!', 'data' => $transfer], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeOrUpdatePengkajianLanjutan(Request $request)
    {

        // dd($request);
        // Check if the registration type is 'rawat-jalan'
        $registration = Registration::find($request->registration_id);

        // Check if a PengkajianNurseRajal already exists for this registration
        $existingPengkajian = PengkajianLanjutan::where('registration_id', $request->registration_id)->where('form_template_id', $request->form_template_id)->first() ?? null;

        try {
            if ($existingPengkajian) {
                $data = $request->except(['_token', '_method', 'status']); // Hapus kolom yang tidak perlu
                $jsonData = json_encode($data);

                $existingPengkajian->update([
                    'form_values' => json_encode($jsonData),
                    'is_final' => $request->status == 1 ? true : false,
                    'modified_by' => auth()->user()->id,
                ]);
                return response()->json(['message' => 'Data saved successfully!', 'data' => $pengkajian], 201);
            } else {
                // Create a new Pengkajian Lanjutan record
                $data = $request->except(['_token', '_method', 'status']); // Hapus kolom yang tidak perlu
                $jsonData = json_encode($data);

                $pengkajian = PengkajianLanjutan::create([
                    'registration_id' => $request->registration_id,
                    'form_template_id' => $request->form_template_id,
                    'form_values' => json_encode($jsonData),
                    'is_final' => $request->status == 1 ? true : false,
                    'created_by' => auth()->user()->id,
                ]);
                return response()->json(['message' => 'Data saved successfully!', 'data' => $pengkajian], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
