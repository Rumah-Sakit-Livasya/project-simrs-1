<?php

namespace App\Http\Controllers;

use App\Models\UploadFile;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function storeKepegawaian(Request $request)
    {
        try {
            $messages = [
                'nama.required' => 'Nama file harus diisi!',
                'employee_id.required' => 'ID Pegawai harus diisi!',
                'file.required' => 'File harus diupload!',
            ];

            // Validate the request with custom messages
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'employee_id' => 'required',
                'file' => 'required|file', // buat lebih spesifik, office, pdf, dan txt
            ], $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = $errors->first(); // Get the first error message
                return response()->json([
                    'error' => $errorMessage
                ], 422);
            }

            // Proses penyimpanan file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $data = $request->all();
                $data['pic'] = $request->employee_id;
                $data['tipe'] = 2; // 1 Umum, 2 Private
                $data['kategori'] = 'Kepegawaian';
                $data['file'] = $fileName;
                $data['hard_copy'] = $request->has('hard_copy') && $request->hard_copy == 'on' ? 1 : 0;

                // Simpan informasi ke database
                UploadFile::create($data);

                $filePath = $file->storeAs('uploads', $fileName, 'public');
                return response()->json([
                    'message' => 'File berhasil diupload!',
                ]);
            } else {
                return response()->json([
                    'error' => 'File tidak ditemukan!',
                ], 404);
            }
        } catch (\Exception $e) {
            // Tangani pengecualian dan kembalikan pesan kesalahan
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $document = UploadFile::find($id);

            if (!$document) {
                return response()->json(['error' => 'Dokumen tidak ditemukan!'], 404);
            }

            return response()->json([
                'id' => $document->id,
                'nama' => $document->nama,
                'file' => $document->file,
                'masa_berlaku' => $document->masa_berlaku,
                'hard_copy' => $document->hard_copy,
                // tambahkan field lain jika diperlukan
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $document = UploadFile::find($id);

            if (!$document) {
                return response()->json(['error' => 'Dokumen tidak ditemukan!'], 404);
            }

            $messages = [
                'nama.required' => 'Nama dokumen tidak boleh kosong!',
            ];

            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'masa_berlaku' => 'nullable|date',
                'file' => 'nullable|file',
            ], $messages);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->first();
                return response()->json([
                    'error' => $errorMessage,
                ], 422);
            }

            $document->nama = $request->nama;

            if ($request->has('masa_berlaku')) {
                $document->masa_berlaku = $request->masa_berlaku;
            }

            $document->hard_copy = $request->has('hard_copy') && $request->hard_copy == 'on' ? 1 : 0;

            // Update file jika ada upload baru
            if ($request->hasFile('file')) {
                // Delete old file if exists
                $oldFilePath = storage_path('app/public/uploads/' . $document->file);
                if ($document->file && file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $fileName, 'public');
                $document->file = $fileName;
            }

            $document->save();

            return response()->json(['message' => 'Dokumen berhasil diupdate!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadDocument($id)
    {
        try {
            $document = UploadFile::find($id);

            if (!$document) {
                return response()->json([
                    'error' => 'File tidak ditemukan!'
                ], 404);
            }

            $filePath = storage_path('app/public/uploads/' . $document->file);

            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => 'File tidak ditemukan!'
                ], 404);
            }

            $originalName = pathinfo($document->file, PATHINFO_BASENAME);
            $newName = preg_replace('/_(\.[a-z0-9]+)$/i', '$1', $originalName);

            if (!empty($document->nama)) {
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = preg_replace('/\s+/', '_', $document->nama) . '.' . $extension;
            }

            $mimeType = \Illuminate\Support\Facades\File::mimeType($filePath);

            return response()->download($filePath, $newName, [
                'Content-Type' => $mimeType,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $document = UploadFile::find($id);

            if (!$document) {
                return response()->json(['error' => 'Dokumen tidak ditemukan!'], 404);
            }

            // Hapus file fisik bila ada
            $filePath = storage_path('app/public/uploads/' . $document->file);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            $document->delete();

            return response()->json(['message' => 'Dokumen berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
