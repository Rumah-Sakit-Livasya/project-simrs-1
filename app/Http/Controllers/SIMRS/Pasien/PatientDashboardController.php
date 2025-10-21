<?php

namespace App\Http\Controllers\SIMRS\Pasien;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Patient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk pasien tertentu.
     * Pastikan Route-Model Binding Anda mengarah ke App\Models\SIMRS\Patient
     */
    public function index(Patient $pasien)
    {
        // Eager load semua data yang relevan
        // Mengambil registrasi, diurutkan dari yang terbaru
        $registrations = $pasien->registration()
            ->with([
                'departement', // Untuk nama Poli/Unit
                'doctor.employee', // Untuk nama Dokter
                'penjamin', // Untuk info penjamin

                // Ini adalah relasi pemeriksaan dari model Registration Anda
                'order_laboratorium', // Data Lab (jika ada)
                'order_radiologi', // Data Radiologi (jika ada)
                'order_tindakan_medis', // Data Tindakan (jika ada)
                'cppt', // Catatan CPPT (jika ada)
                'order_gizi' // Order Gizi (jika ada)
                // Tambahkan relasi lain di sini jika perlu (cth: resep/farmasi)
            ])
            ->orderBy('created_at', 'desc') // Tampilkan kunjungan terbaru di atas
            ->get();

        return view('app-type.simrs.pasien.dashboard', [ // Kita gunakan view baru
            'pasien' => $pasien,
            'registrations' => $registrations
        ]);
    }
}
