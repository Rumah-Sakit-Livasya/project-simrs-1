<?php

declare(strict_types=1);

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class PengkajianController extends Controller
{
    /**
     * Mengambil data pengkajian perawat rawat jalan.
     *
     * @param Request $request
     * @param string $type
     * @param string $registration_number
     * @return JsonResponse
     */
    public function getPengkajianRajal(Request $request, string $type, string $registration_number): JsonResponse
    {
        try {
            $registration = Registration::where('registration_number', $registration_number)
                ->where('registration_type', $type)
                ->firstOrFail();

            $pengkajian = $registration->pengkajian_nurse_rajal()->with('signature')->first();

            if ($pengkajian) {
                return response()->json($pengkajian, 200);
            }

            return response()->json(['message' => 'Data pengkajian belum dibuat.'], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Registrasi tidak ditemukan!'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data transfer pasien antar ruangan.
     *
     * @param string $registration_id
     * @return JsonResponse
     */
    public function getTransferPasienAntarRuangan(string $registration_id): JsonResponse
    {
        try {
            $transfer = TransferPasienAntarRuangan::where('registration_id', $registration_id)
                ->with('signatures')
                ->firstOrFail();
            return response()->json($transfer, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data transfer pasien tidak ditemukan!'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan atau memperbarui Pengkajian Rawat Jalan.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeOrUpdatePengkajianRajal(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            // Tambahkan validasi lain untuk field PengkajianNurseRajal di sini
            'signature_data' => 'nullable|array',
            'signature_data.pic' => 'nullable|string',
            'signature_data.role' => 'nullable|string',
            'signature_data.signature_image' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            if (!$userId) {
                throw new Exception("Sesi Anda telah berakhir. Silakan login kembali.", 401);
            }

            // Gunakan firstOrNew untuk menangani created_by dengan benar
            $pengkajian = PengkajianNurseRajal::firstOrNew(
                ['registration_id' => $validatedData['registration_id']]
            );

            // Isi data dari request
            $pengkajian->fill($request->except(['_token', '_method', 'signature_data']));
            $pengkajian->modified_by = $userId;
            if (!$pengkajian->exists) {
                $pengkajian->created_by = $userId;
            }
            $pengkajian->save();

            // Logika penyimpanan tanda tangan
            $signatureData = $validatedData['signature_data'] ?? null;
            if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                $oldPath = optional($pengkajian->signature)->signature;
                $newPath = $this->saveSignatureFile($signatureData['signature_image'], "pengkajian-rajal_{$pengkajian->id}");

                // Pastikan field 'role' diisi, gunakan default 'perawat' jika tidak ada
                $role = $signatureData['role'] ?? 'perawat';

                $pengkajian->signature()->updateOrCreate(
                    [
                        'signable_id' => $pengkajian->id,
                        'signable_type' => PengkajianNurseRajal::class,
                        'role' => $role,
                    ],
                    [
                        'signature' => $newPath,
                        'pic' => $signatureData['pic'],
                        'role' => $role,
                    ]
                );

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data pengkajian berhasil disimpan!', 'data' => $pengkajian->load('signature')], 200);
        } catch (Exception $e) {
            DB::rollBack();
            $statusCode = ($e->getCode() === 401) ? 401 : 500;
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], $statusCode);
        }
    }

    /**
     * Menyimpan atau memperbarui Transfer Pasien Antar Ruangan.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeOrUpdateTransferPasienAntarRuangan(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            // ... validasi lain untuk transfer pasien
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

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            if (!$userId) {
                throw new Exception("Sesi Anda telah berakhir. Silakan login kembali.", 401);
            }

            // Logika untuk data utama transfer
            $transfer = TransferPasienAntarRuangan::firstOrNew(
                ['registration_id' => $validatedData['registration_id']]
            );

            $transfer->fill($request->except(['_token', '_method', 'data_ttd1', 'data_ttd2', 'data_ttd3', 'data_ttd4']));
            $transfer->modified_by = $userId;
            $transfer->user_id = $userId;
            if (!$transfer->exists) {
                $transfer->created_by = $userId;
            }
            $transfer->save();

            // Logika penyimpanan tanda tangan
            $signatureMap = ['data_ttd1' => 'pengirim', 'data_ttd2' => 'penerima', 'data_ttd3' => 'pengirim_balik', 'data_ttd4' => 'penerima_balik'];
            foreach ($signatureMap as $requestKey => $role) {
                $signatureData = $validatedData[$requestKey] ?? null;
                if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                    $oldPath = optional($transfer->signatures()->where('role', $role)->first())->signature;
                    $newPath = $this->saveSignatureFile($signatureData['signature_image'], "transfer-pasien_{$transfer->id}_{$role}");

                    $transfer->signatures()->updateOrCreate(
                        ['role' => $role],
                        ['pic' => $signatureData['pic'], 'signature' => $newPath]
                    );

                    if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data transfer pasien berhasil disimpan!', 'data' => $transfer->load('signatures')], 200);
        } catch (Exception $e) {
            DB::rollBack();
            $statusCode = ($e->getCode() === 401) ? 401 : 500;
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], $statusCode);
        }
    }

    /**
     * [REFACTORED] Menyimpan atau memperbarui data dari form dinamis (Pengkajian Lanjutan).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeOrUpdatePengkajianLanjutan(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'registration_id'  => 'required|exists:registrations,id',
            'form_template_id' => 'required|exists:form_templates,id',
            'form_values'      => 'required|array',
            'is_final'         => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            if (!$userId) {
                throw new Exception("Sesi Anda telah berakhir. Silakan login kembali.", 401);
            }

            // Gunakan firstOrNew untuk mendapatkan instance model (baik yang sudah ada atau yang baru)
            $pengkajian = PengkajianLanjutan::firstOrNew(
                [
                    // Kunci untuk mencari
                    'registration_id'  => $validatedData['registration_id'],
                    'form_template_id' => $validatedData['form_template_id'],
                ]
            );

            // Isi atau perbarui data
            $pengkajian->form_values = json_encode($validatedData['form_values']);
            $pengkajian->is_final    = $validatedData['is_final'];
            $pengkajian->modified_by = $userId; // Selalu set/update modified_by

            // Set created_by hanya jika record ini baru dibuat
            if (!$pengkajian->exists) {
                $pengkajian->created_by = $userId;
            }

            $pengkajian->save();

            DB::commit();

            return response()->json([
                'message' => 'Data pengkajian lanjutan berhasil disimpan!',
                'data'    => $pengkajian
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error'   => 'Data yang dikirim tidak valid.',
                'message' => 'Terdapat kesalahan pada data yang Anda masukkan.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            $statusCode = ($e->getCode() === 401) ? 401 : 500;
            return response()->json([
                'error'   => 'Terjadi kesalahan saat menyimpan data.',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * [BARU] Menghapus data Pengkajian Lanjutan.
     *
     * @param PengkajianLanjutan $pengkajianLanjutan Instance dari Route Model Binding.
     * @return JsonResponse
     */
    public function destroyPengkajianLanjutan(PengkajianLanjutan $pengkajianLanjutan): JsonResponse
    {
        // Aturan Bisnis: Jangan izinkan penghapusan jika form sudah difinalisasi.
        if ($pengkajianLanjutan->is_final) {
            return response()->json([
                'error' => 'Aksi Ditolak',
                'message' => 'Form yang sudah difinalisasi tidak dapat dihapus.'
            ], 403); // 403 Forbidden
        }

        // Aturan Bisnis Opsional: Hanya user yang membuat yang bisa menghapus, atau admin.
        // if (Auth::id() !== $pengkajianLanjutan->created_by && !Auth::user()->isAdmin()) {
        //     return response()->json([
        //         'error' => 'Akses Ditolak',
        //         'message' => 'Anda tidak memiliki izin untuk menghapus form ini.'
        //     ], 403);
        // }

        DB::beginTransaction();
        try {
            // Lakukan penghapusan data.
            // Jika Anda menggunakan SoftDeletes pada model, ini akan mengisi kolom 'deleted_at'.
            // Jika tidak, ini akan menghapus permanen dari database.
            $pengkajianLanjutan->delete();

            // Commit transaksi jika berhasil.
            DB::commit();

            // Berikan respons sukses.
            return response()->json([
                'message' => 'Data pengkajian berhasil dihapus.'
            ], 200);
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error.
            DB::rollBack();

            // Catat error untuk debugging.
            Log::error('Gagal menghapus Pengkajian Lanjutan ID ' . $pengkajianLanjutan->id . ': ' . $e->getMessage());

            // Berikan respons error ke frontend.
            return response()->json([
                'error' => 'Gagal Menghapus',
                'message' => 'Terjadi kesalahan saat mencoba menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Helper function untuk menyimpan file tanda tangan dari data base64.
     *
     * @param string $base64Image
     * @param string $fileNameBasis
     * @return string
     */
    private function saveSignatureFile(string $base64Image, string $fileNameBasis): string
    {
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $pathParts = explode('_', $fileNameBasis, 2);
        $folder = str_replace('-', '_', $pathParts[0]);
        $name = $pathParts[1];
        $imageName = "ttd_{$name}_" . time() . '.png';
        $path = "signatures/{$folder}/{$imageName}";

        Storage::disk('public')->put($path, $image);

        return $path;
    }

    /**
     * Menampilkan halaman form yang sudah diisi dalam mode LIHAT/CETAK (read-only).
     * Method ini dipanggil oleh route `poliklinik.pengkajian-lanjutan.show`.
     *
     * @param PengkajianLanjutan $pengkajianLanjutan Instance model dari Route Model Binding.
     * @return View
     */
    public function showFilledForm(PengkajianLanjutan $pengkajianLanjutan): View
    {
        // Panggil helper method dengan flag isEditMode = false
        return $this->prepareAndShowForm($pengkajianLanjutan, false);
    }

    /**
     * Menampilkan halaman form yang sudah diisi dalam mode EDIT.
     * Method ini dipanggil oleh route `poliklinik.pengkajian-lanjutan.edit`.
     *
     * @param PengkajianLanjutan $pengkajianLanjutan Instance model dari Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function editFilledForm(PengkajianLanjutan $pengkajianLanjutan)
    {
        // Aturan Bisnis: Jangan izinkan edit jika form sudah difinalisasi.
        if ($pengkajianLanjutan->is_final) {
            // Redirect kembali ke halaman sebelumnya dengan pesan error.
            return back()->with('error', 'Form yang sudah difinalisasi tidak dapat diubah lagi.');

            // Alternatif lain: Tampilkan halaman 'show' dengan pesan error di atasnya.
            // return redirect()->route('poliklinik.pengkajian-lanjutan.show', $pengkajianLanjutan->id)
            //     ->with('error', 'Form yang sudah difinalisasi tidak dapat diubah lagi.');
        }

        // Panggil helper method dengan flag isEditMode = true
        return $this->prepareAndShowForm($pengkajianLanjutan, true);
    }

    /**
     * Private helper method untuk menghindari duplikasi kode.
     * Method ini menyiapkan semua data yang dibutuhkan dan merender view.
     *
     * @param PengkajianLanjutan $pengkajian
     * @param boolean $isEditMode
     * @return View
     */
    private function prepareAndShowForm(PengkajianLanjutan $pengkajian, bool $isEditMode): \Illuminate\View\View
    {
        try {
            // 1. Eager Load relasi.
            $pengkajian->load(['form_template', 'registration.patient', 'creator', 'editor']);

            // 2. [DIPERBAIKI DENGAN LOGIKA AMAN]
            // Cek tipe data dari form_values.
            $formValuesData = $pengkajian->form_values;

            if (is_string($formValuesData)) {
                // Jika masih berupa string, decode.
                $formValues = json_decode($formValuesData, true) ?? [];
            } elseif (is_array($formValuesData)) {
                // Jika sudah berupa array (karena casting berhasil), langsung gunakan.
                $formValues = $formValuesData;
            } else {
                // Jika null atau tipe data lain, gunakan array kosong sebagai default.
                $formValues = [];
            }

            // 3. Render view.
            return view('pages.simrs.poliklinik.pengkajian-lanjutan.show', [
                'pengkajian'    => $pengkajian,
                'formTemplate'  => $pengkajian->form_template,
                'formValues'    => $formValues,
                'isEditMode'    => $isEditMode,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal memuat form pengkajian lanjutan: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan saat memuat data form. Silakan coba lagi nanti.');
        }
    }
}
