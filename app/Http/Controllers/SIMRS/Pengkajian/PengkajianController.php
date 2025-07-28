<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        // Ambil data registrasi
        $registration = Registration::find($request->registration_id);

        if (!$registration) {
            return response()->json(['error' => 'Registration not found.'], 404);
        }

        // Cek apakah pengkajian sudah ada
        $existingPengkajian = $registration->pengkajian_nurse_rajal;

        // Siapkan data JSON
        $sensorik = json_encode([
            'sensorik_penglihatan' => $request->sensorik_penglihatan,
            'sensorik_penciuman' => $request->sensorik_penciuman,
            'sensorik_pendengaran' => $request->sensorik_pendengaran,
        ]);

        $motorik = json_encode([
            'motorik_aktifitas' => $request->motorik_aktifitas,
            'motorik_berjalan' => $request->motorik_berjalan,
        ]);

        // Siapkan data utama
        $data = $request->only([
            'tgl_masuk',
            'jam_masuk',
            'tgl_dilayani',
            'jam_dilayani',
            'keluhan_utama',
            'pr',
            'rr',
            'bp',
            'temperatur',
            'body_height',
            'body_weight',
            'bmi',
            'kat_bmi',
            'sp02',
            'lingkar_kepala',
            'diagnosa_keperawatan',
            'rencana_tindak_lanjut',
            'alergi_obat',
            'ket_alergi_obat',
            'alergi_makanan',
            'ket_alergi_makanan',
            'alergi_lainnya',
            'ket_alergi_lainnya',
            'reaksi_alergi_obat',
            'reaksi_alergi_makanan',
            'reaksi_alergi_lainnya',
            'skor_nyeri',
            'provokatif',
            'quality',
            'region',
            'time',
            'nyeri',
            'nyeri_hilang',
            'penurunan_bb',
            'asupan_makan',
            'status_psikologis',
            'status_spiritual',
            'masalah_prilaku',
            'kekerasan_dialami',
            'hub_dengan_keluarga',
            'tempat_tinggal',
            'kerabat_dihub',
            'no_kontak_kerabat',
            'penghasilan',
            'hambatan_lainnya',
            'kebutuhan_penerjemah',
            'pembelajaran_lainnya',
            'kognitif',
            'registration_id',
            'user_id'
        ]);

        // Boolean
        $data['gelang'] = $request->boolean('gelang');

        // Data JSON kompleks
        $data['kondisi_khusus'] = json_encode($request->kondisi_khusus);
        $data['imunisasi_dasar'] = json_encode($request->imunisasi_dasar);
        $data['resiko_jatuh'] = json_encode($request->resiko_jatuh);
        $data['hambatan_belajar'] = json_encode($request->hambatan_belajar);
        $data['kebutuhan_pembelajaran'] = json_encode($request->kebutuhan_pembelajaran);
        $data['sensorik'] = $sensorik;
        $data['motorik'] = $motorik;
        $data['updated_at'] = now();

        try {
            if ($existingPengkajian) {
                // Update existing
                $data['modified_by'] = $request->user_id;
                $existingPengkajian->update($data);
                $pengkajian = $existingPengkajian;
            } else {
                // Create new
                $data['created_by'] = $request->user_id;
                $pengkajian = PengkajianNurseRajal::create($data);
            }

            // Handle signature jika ada
            if ($request->filled('signature_image')) {
                $imageData = $request->input('signature_image');
                $image = base64_decode(str_replace('data:image/png;base64,', '', str_replace(' ', '+', $imageData)));
                $imageName = 'ttd_' . time() . '.png';
                $path = 'signatures/' . $imageName;

                // Hapus tanda tangan lama jika ada
                if ($pengkajian->signature && \Storage::disk('public')->exists($pengkajian->signature->signature)) {
                    \Storage::disk('public')->delete($pengkajian->signature->signature);
                }

                // Simpan file baru
                \Storage::disk('public')->put($path, $image);

                // Simpan/Update relasi signature
                $pengkajian->signature()->updateOrCreate(
                    [
                        'signable_id' => $pengkajian->id,
                        'signable_type' => get_class($pengkajian),
                    ],
                    [
                        'signature' => $path,
                        'pic' => $request->input('pic'),
                        'role' => $request->input('role'),
                    ]
                );
            }

            return response()->json([
                'message' => 'Data saved successfully!',
                'data' => $pengkajian,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan atau memperbarui data transfer pasien beserta tanda tangannya.
     */
    public function storeOrUpdateTransferPasienAntarRuangan(Request $request)
    {
        // 1. VALIDASI DATA DENGAN ATURAN YANG DIPERBAIKI
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'tgl' => 'nullable|string',
            'jam' => 'nullable|string',
            'tgl_masuk_pasien' => 'nullable|string',
            'jam_masuk_pasien' => 'nullable|string',
            'dokter' => 'nullable|string',
            'ruangan_asal' => 'nullable|string',
            'ruangan_pindah' => 'nullable|string',
            'keluhan_utama' => 'nullable|string',
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
            'pemeriksaan_penunjang' => 'nullable|string',
            'diet' => 'nullable|string',
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
            'pasien_kelmbali' => 'nullable|string',
            'keadaan_umum_after' => 'nullable|string',
            'td_after' => 'nullable|string',
            'nd_after' => 'nullable|string',
            'rr_after' => 'nullable|string',
            'sb_after' => 'nullable|string',
            'rj_after' => 'nullable|string',
            'diet_after' => 'nullable|string',

            // PERBAIKAN: Ubah 'required_with' menjadi 'nullable'
            'data_ttd1' => 'nullable|array',
            'data_ttd1.pic' => 'nullable|string',
            'data_ttd1.signature_image' => 'nullable|string',

            'data_ttd2' => 'nullable|array',
            'data_ttd2.pic' => 'nullable|string',
            'data_ttd2.signature_image' => 'nullable|string',

            'data_ttd3' => 'nullable|array',
            'data_ttd3.pic' => 'nullable|string',
            'data_ttd3.signature_image' => 'nullable|string',

            'data_ttd4' => 'nullable|array',
            'data_ttd4.pic' => 'nullable|string',
            'data_ttd4.signature_image' => 'nullable|string',
        ]);

        // 2. GUNAKAN DATABASE TRANSACTION
        DB::beginTransaction();
        try {
            // Simpan data utama form
            $transferData = $request->except(['data_ttd1', 'data_ttd2', 'data_ttd3', 'data_ttd4', '_token', '_method']);
            $transferData['user_id'] = auth()->id();

            $transfer = TransferPasienAntarRuangan::updateOrCreate(
                ['registration_id' => $validatedData['registration_id']],
                $transferData
            );

            // =====================================================================
            // KUNCI PERBAIKAN: LOGIKA PENYIMPANAN TANDA TANGAN YANG CERDAS
            // =====================================================================
            $signatureMap = [
                'data_ttd1' => 'pengirim',
                'data_ttd2' => 'penerima',
                'data_ttd3' => 'pengirim_balik',
                'data_ttd4' => 'penerima_balik',
            ];

            // Baris '$transfer->signatures()->delete()' telah DIHAPUS.

            foreach ($signatureMap as $requestKey => $role) {
                $signatureData = $request->input($requestKey);

                // Proses HANYA JIKA ada gambar base64 BARU yang dikirim untuk slot ini
                if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                    // Ada gambar baru, maka kita proses dan simpan/update
                    $path = $this->saveSignature($signatureData['signature_image']);

                    // updateOrCreate akan memperbarui TTD yang ada (berdasarkan role) atau membuat yang baru.
                    $transfer->signatures()->updateOrCreate(
                        ['role' => $role], // Kunci untuk mencari
                        [
                            'pic'       => $signatureData['pic'] ?? auth()->user()->name,
                            'signature' => $path, // Simpan path baru
                        ]
                    );
                }
                // JIKA tidak ada gambar baru, JANGAN LAKUKAN APA-APA.
                // Data lama di database akan tetap aman.
            }

            DB::commit();

            return response()->json([
                'message' => 'Data transfer pasien berhasil disimpan!',
                'data' => $transfer->load('signatures')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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

    /**
     * Helper function untuk menyimpan gambar tanda tangan dari data base64.
     *
     * @param string $base64Image Data gambar dalam format base64
     * @return string Path file yang disimpan
     */
    private function saveSignature($base64Image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('Tipe gambar tidak valid.');
            }
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                throw new \Exception('Gagal decode base64.');
            }
        } else {
            throw new \Exception('Format data URI base64 tidak valid.');
        }

        // Buat nama file yang unik di dalam folder 'signatures'
        $fileName = 'signatures/' . uniqid() . '_' . time() . '.' . $type;

        Storage::disk('public')->put($fileName, $imageData);
        return $fileName;
    }
}
