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
                'file' => 'required|file',
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

    public function downloadDocument($id)
    {
        try {
            // Temukan dokumen berdasarkan ID
            $document = UploadFile::find($id);

            if (!$document) {
                return response()->json(['error' => 'Dokumen tidak ditemukan!'], 404);
            }

            // Path ke file yang disimpan
            $filePath = storage_path('app/public/uploads/' . $document->file);

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'File tidak ditemukan!'], 404);
            }

            // Mengembalikan file PDF sebagai response download
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $document->file . '"'
            ]);
        } catch (\Exception $e) {
            // Tangani pengecualian dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        dd(true);
    }
}
