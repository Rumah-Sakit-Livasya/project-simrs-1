<?php

namespace App\Http\Controllers\SIMRS; // Sesuaikan dengan namespace Anda

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DynamicFormController extends Controller
{
    /**
     * Mengambil template form HTML berdasarkan ID.
     */
    public function getFormTemplate($template_id)
    {
        try {
            $template = FormTemplate::findOrFail($template_id);
            // Anda bisa juga mengambil data yang sudah ada jika ingin mengisi form
            // $existingData = FormSubmission::where(...)->first();
            // Lalu lakukan string replacement pada $template->form_source

            return response()->json([
                'form_source' => $template->form_source
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Template form tidak ditemukan'], 404);
        }
    }

    /**
     * Menyimpan atau memperbarui data dari form dinamis.
     */
    public function storeFormSubmission(Request $request)
    {
        // 1. Validasi data meta
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'form_template_id' => 'required|exists:form_templates,id',
            'form_values' => 'required|array', // Pastikan frontend mengirim data dalam key 'form_values'
            'is_final' => 'required|boolean',
        ]);

        // 2. Gunakan Transaction untuk keamanan
        DB::beginTransaction();
        try {
            // 3. Gunakan updateOrCreate untuk handle create dan update
            $submission = FormSubmission::updateOrCreate(
                [
                    // Kunci untuk mencari data yang sudah ada
                    'registration_id' => $validatedData['registration_id'],
                    'form_template_id' => $validatedData['form_template_id'],
                ],
                [
                    // Data untuk disimpan atau diperbarui
                    'form_values' => $validatedData['form_values'],
                    'is_final' => $validatedData['is_final'],
                    'user_id' => auth()->id(),
                ]
            );

            // 4. Commit transaksi
            DB::commit();

            return response()->json([
                'message' => 'Data berhasil disimpan!',
                'data' => $submission
            ], 200);
        } catch (Exception $e) {
            // 5. Rollback jika ada error
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
