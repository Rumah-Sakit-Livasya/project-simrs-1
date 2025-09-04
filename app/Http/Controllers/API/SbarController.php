<?php

// app/Http/Controllers/Api/SbarController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sbar;
use App\Models\SIMRS\CPPT\CPPT;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SbarController extends Controller
{
    public function index()
    {
        $data = Sbar::with(['cppt.registration.patient', 'user']);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('patient_info', function ($row) {
                return $row->cppt->registration->patient->name ?? 'N/A';
            })
            ->addColumn('created_by', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                // Anda bisa menambahkan otorisasi di sini jika perlu
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn">Edit</a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn">Hapus</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // app/Http/Controllers/SIMRS/CPPT/CPPTController.php

    /**
     * Menyimpan atau memperbarui data SBAR yang terkait dengan sebuah CPPT.
     * Alur ini mengasumsikan satu CPPT hanya bisa memiliki satu SBAR.
     */
    public function storeSbar(Request $request)
    {
        // 1. Validasi (mengikuti pola patokan)
        $validatedData = $request->validate([
            'cppt_id'      => 'required|string',
            'situation'      => 'required|string',
            'background'     => 'required|string',
            'assessment'     => 'required|string',
            'recommendation' => 'required|string',
            // Validasi untuk array tanda tangan
            'signatures' => 'required|array',
            'signatures.penerima.pic' => 'required|string',
            'signatures.penerima.signature_image' => 'required|string',
            'signatures.pemberi.pic' => 'required|string',
            'signatures.pemberi.signature_image' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 2. Logika untuk data utama SBAR (Update or Create)
            $sbar = Sbar::firstOrNew(
                ['cppt_id' => $validatedData['cppt_id']]
            );

            $sbar->fill($validatedData); // Isi field S, B, A, R
            $sbar->user_id = Auth::id(); // Selalu catat user terakhir yang memodifikasi
            $sbar->save();

            // =========================
            // === CODINGAN UNTUK TTD ===
            // =========================
            // 3. Logika penyimpanan tanda tangan (refactor: pastikan data signature masuk ke database, baik ada gambar baru atau tidak)
            $signatureMap = ['penerima', 'pemberi']; // Role yang kita harapkan

            foreach ($signatureMap as $role) {
                // Ambil data signature untuk role saat ini dari request
                $signatureData = $validatedData['signatures'][$role] ?? null;

                // Ambil signature model lama (jika ada)
                $oldSignatureModel = $sbar->signatures()->where('role', $role)->first();
                $oldPath = optional($oldSignatureModel)->signature;

                $newPath = $oldPath; // Default: gunakan path lama jika tidak ada gambar baru

                // Jika ada gambar signature baru (base64)
                if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                    // Simpan file baru ke folder 'sbar' dan dapatkan path-nya (menggunakan fungsi helper)
                    // Simpan file signature base64 ke folder 'sbar' di storage/app/public/sbar
                    $base64Image = $signatureData['signature_image'];
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $fileName = "sbar_{$sbar->id}_{$role}_" . time() . '.png';
                    $filePath = 'sbar/' . $fileName;
                    Storage::disk('public')->put($filePath, $imageData);
                    $newPath = $filePath;
                }

                // Lakukan Update or Create untuk signature dengan role ini
                // (Jika tidak ada gambar baru, tetap update pic jika berubah)
                if (!empty($signatureData)) {
                    $signature = $sbar->signatures()->updateOrCreate(
                        ['role' => $role], // Kunci unik untuk mencari
                        [
                            'pic' => $signatureData['pic'] ?? null, // Data baru
                            'signature' => $newPath                 // Path signature (bisa lama/bisa baru)
                        ]
                    );

                    // Jika gagal menyimpan signature, rollback dan tampilkan pesan gagal
                    if (!$signature) {
                        DB::rollBack();
                        return response()->json(['error' => 'Gagal menyimpan tanda tangan untuk role: ' . $role], 500);
                    }

                    // Hapus file tanda tangan lama jika ada file baru dan path lama berbeda
                    if (
                        !empty($signatureData['signature_image']) &&
                        str_starts_with($signatureData['signature_image'], 'data:image') &&
                        $oldPath &&
                        $oldPath !== $newPath &&
                        Storage::disk('public')->exists($oldPath)
                    ) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }
            // === END CODINGAN UNTUK TTD ===

            DB::commit();
            return response()->json(['message' => 'Data SBAR berhasil disimpan!', 'data' => $sbar->load('signatures')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan SBAR: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan SBAR.'], 500);
        }
    }


    public function edit(Sbar $sbar)
    {
        return response()->json($sbar);
    }

    public function update(Request $request, Sbar $sbar)
    {
        $validated = $request->validate([
            'cppt_id' => 'required|exists:cppt,id',
            'situation' => 'required|string',
            'background' => 'required|string',
            'assessment' => 'required|string',
            'recommendation' => 'required|string',
        ]);
        $sbar->update($validated);
        return response()->json(['success' => 'SBAR berhasil diperbarui.']);
    }

    public function destroy(Sbar $sbar)
    {
        $sbar->delete(); // Ini akan melakukan soft delete
        return response()->json(['success' => 'SBAR berhasil dihapus.']);
    }
}
