<?php

namespace App\Http\Controllers\SIMRS\ResumeMedisRajal;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResumeMedisRajalController extends Controller
{
    public function getResumeMedis(Request $request, $type, $registration_number)
    {
        try {
            $registration = Registration::where('registration_number', $registration_number)->where('registration_type', $type)->first();
            $resume = $registration->resume_medis_rajal;
            if ($resume) {
                return response()->json($resume, 200);
            } else {

                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan atau memperbarui data Resume Medis Rawat Jalan.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi sesuai struktur request
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'nama_pasien' => 'required|string',
            'medical_record_number' => 'required|string',
            'tgl_lahir' => 'required|string', // Awalnya string, akan diparse
            'tgl_masuk' => 'required|date',
            'diagnosa_utama' => 'required|string',

            // Validasi field signature sebagai array (sama seperti CPPTController)
            'signature_data' => 'nullable|array',
            'signature_data.pic' => 'nullable|string',
            'signature_data.signature_image' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 2. Ambil data kecuali field khusus
            $data = $request->except([
                '_token',
                '_method',
                'signature_image',
                'pic',
                'role',
                'action_type',
            ]);

            // 3. Format tanggal lahir
            $dateString = str_replace('-', '/', $validatedData['tgl_lahir']);
            $date = DateTime::createFromFormat('d/m/Y', $dateString);
            if (! $date) {
                throw new Exception('Format tanggal lahir tidak valid. Harap gunakan dd/mm/yyyy atau dd-mm-yyyy.');
            }
            $data['tgl_lahir'] = $date->format('Y-m-d');

            // 4. Encode field JSON
            $data['awal_rencana_tindak_lanjut'] = json_encode($request->awal_rencana_tindak_lanjut);
            $data['awal_evaluasi_penyakit'] = json_encode($request->awal_evaluasi_penyakit);
            $data['awal_edukasi'] = json_encode($request->awal_edukasi);
            $data['asesmen_dilakukan_melalui'] = json_encode($request->asesmen_dilakukan_melalui);

            // 5. Set field tambahan
            $data['pic_dokter'] = auth()->id();
            $data['user_id'] = auth()->id();
            $data['is_final'] = ($request->action_type == 'final') ? 1 : 0;
            $data['is_ttd'] = true; // ✅ FIX: supaya tidak NULL

            // 6. Simpan atau update data utama
            $resume = ResumeMedisRajal::updateOrCreate(
                ['registration_id' => $validatedData['registration_id']],
                $data
            );

            // 7. Simpan tanda tangan jika ada (sama seperti CPPTController)
            $signatureData = $validatedData['signature_data'] ?? null;
            if (! empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                $oldPath = optional($resume->signature)->signature;
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', str_replace(' ', '+', $signatureData['signature_image'])));
                $imageName = 'ttd_resume_medis_rajal_'.$resume->id.'_'.time().'.png';
                $newPath = 'signatures/'.$imageName;
                Storage::disk('public')->put($newPath, $image);

                $resume->signature()->updateOrCreate(
                    [
                        'signable_id' => $resume->id,
                        'signable_type' => get_class($resume),
                    ],
                    [
                        'signature' => $newPath,
                        'pic' => $signatureData['pic'] ?? null,
                        'role' => $signatureData['role'] ?? 'dokter',
                    ]
                );

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 8. Commit transaksi
            DB::commit();

            return response()->json([
                'message' => 'Resume Medis berhasil disimpan!',
                'data' => $resume->load('signature'),
            ], 200);
        } catch (Exception $e) {
            // 9. Rollback jika error
            DB::rollBack();

            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper function untuk memproses dan menyimpan file tanda tangan dari base64.
     *
     * @param  string  $base64Image  String base64 dari gambar.
     * @param  string  $fileNameBasis  Bagian unik dari nama file (tanpa ekstensi).
     * @return string Path file yang tersimpan.
     */
    private function saveSignatureFile(string $base64Image, string $fileNameBasis): string
    {
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

        $pathParts = explode('_', $fileNameBasis, 2);
        $folder = str_replace('-', '_', $pathParts[0]); // e.g., 'resume_medis_rajal'
        $name = $pathParts[1];

        $imageName = "ttd_{$name}_".time().'.png';
        $path = "signatures/{$folder}/{$imageName}";

        Storage::disk('public')->put($path, $image);

        return $path;
    }
}
