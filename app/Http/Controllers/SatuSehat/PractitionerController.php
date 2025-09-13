<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PractitionerController extends Controller
{
    public function index($category = 'pegawai')
    {
        // Eager load relasi yang dibutuhkan untuk performa
        $query = Employee::with('jobPosition');

        // Logika filtering berdasarkan kategori dari URL
        switch ($category) {
            case 'dokter':
                $query->where('is_doctor', 1);
                break;
            case 'bidan':
                $query->whereHas('jobPosition', fn($q) => $q->where('name', 'like', '%Bidan%'));
                break;
            case 'perawat':
                $query->whereHas('jobPosition', fn($q) => $q->where('name', 'like', '%Perawat%'));
                break;
            case 'therapist':
                $query->whereHas('jobPosition', fn($q) => $q->where('name', 'like', '%Therapist%')->orWhere('name', 'like', '%Analis%'));
                break;
            case 'radiografer':
                $query->whereHas('jobPosition', fn($q) => $q->where('name', 'like', '%Radiografer%'));
                break;
            case 'apoteker':
                $query->whereHas('jobPosition', fn($q) => $q->where('name', 'like', '%Apoteker%'));
                break;
            case 'pegawai':
            default:
                // Tidak ada filter tambahan, tampilkan semua
                break;
        }

        $employees = $query->where('is_active', 1)->get();

        return view('pages.simrs.satu-sehat.practitioners', [
            'employees' => $employees,
            'activeCategory' => $category
        ]);
    }

    public function map(Request $request, Employee $employee)
    {
        // Validasi Kunci: Pastikan NIK/KTP ada
        if (empty($employee->identity_number)) {
            return response()->json([
                'msg' => 'gagal',
                'text' => 'Nomor KTP (NIK) kosong. Mohon lengkapi data pegawai terlebih dahulu.'
            ], 400); // 400 Bad Request
        }

        try {
            // --- LOGIKA MAPPING SEBENARNYA DI SINI ---
            // Panggil API Satu Sehat, kirim data dari $employee (terutama NIK)
            // $satuSehatService = new SatuSehatService();
            // $response = $satuSehatService->registerPractitioner($employee);
            // $newPractitionerId = $response['id'];
            // ------------------------------------------

            // Untuk simulasi, kita generate UUID baru secara acak
            $newPractitionerId = (string) Str::uuid();

            // Update record di database dengan ID baru dari Satu Sehat
            $employee->update(['satu_sehat_practitioner_id' => $newPractitionerId]);

            return response()->json([
                'msg' => 'success',
                'text' => 'Tenaga Kesehatan berhasil di-mapping!',
                'practitioner_id' => $newPractitionerId
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal Mapping Nakes: ' . $e->getMessage());
            return response()->json(['msg' => 'error', 'text' => 'Terjadi kesalahan internal server.'], 500);
        }
    }
}
