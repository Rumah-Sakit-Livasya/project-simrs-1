<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SIMRS\Registration;
use App\Models\FhirLog;

class LaporanSummaryController extends Controller
{
    /**
     * Menampilkan halaman utama Laporan Summary.
     */
    public function index()
    {
        return view('pages.simrs.satu-sehat.laporan-summary');
    }

    /**
     * Mengambil dan memformat data untuk DataTables.
     */
    public function getData(Request $request)
    {
        // Validasi dan parsing input
        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'))->endOfDay();

        // Query dasar dengan relasi yang dibutuhkan (eager loading)
        $query = Registration::with(['patient', 'fhirLogs'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Terapkan filter kondisional
        $query->when($request->norm, function ($q, $norm) {
            return $q->whereHas('patient', fn($subq) => $subq->where('no_rm', $norm));
        });
        $query->when($request->noreg, fn($q, $noreg) => $q->where('no_registrasi', $noreg));
        $query->when($request->nama_pasien, function ($q, $name) {
            return $q->whereHas('patient', fn($subq) => $subq->where('name', 'like', "%{$name}%"));
        });
        $query->when($request->stat_pasien, function ($q, $status) {
            // Asumsi ada kolom 'status_pulang' (boolean) di tabel registrations
            return $q->where('status_pulang', $status === 't');
        });
        $query->when($request->tipe_pasien, fn($q, $tipe) => $q->where('jenis_registrasi', strtoupper($tipe)));

        $registrations = $query->get();

        // Format data untuk frontend
        $formattedData = $registrations->map(function ($reg) {
            $logs = $reg->fhirLogs->keyBy('resource_type');
            $encounterStatus = $logs->get('Encounter') ? ($logs->get('Encounter')->is_success ? 'Berhasil' : 'Gagal') : 'Belum';

            return [
                'no_registrasi' => $reg->no_registrasi,
                'no_rm' => $reg->patient->no_rm,
                'nama_pasien' => $reg->patient->name,
                'encounter' => $encounterStatus,
                'condition' => $logs->get('Condition') && $logs->get('Condition')->is_success ? 'Berhasil' : 'Gagal',
                'observation' => $logs->get('Observation') && $logs->get('Observation')->is_success ? 'Berhasil' : 'Gagal',
                // Tambahkan resource lain di sini
                'action' => $encounterStatus === 'Gagal' ? $reg->id : null,
            ];
        });

        return response()->json(['data' => $formattedData]);
    }

    /**
     * Mengirim ulang data Encounter yang gagal.
     */
    public function resendEncounter(Request $request, Registration $registration)
    {
        // --- LOGIKA KIRIM ULANG SEBENARNYA DI SINI ---
        // Panggil service class Anda untuk mengirim ulang data $registration ke API Satu Sehat
        // $satuSehatService = new SatuSehatService();
        // $success = $satuSehatService->sendEncounter($registration);
        // ------------------------------------------

        // Simulasi
        $success = true;

        if ($success) {
            // Update log atau status di database jika perlu
            return response()->json(['message' => 'Data Encounter berhasil dikirim ulang!']);
        }

        return response()->json(['message' => 'Gagal mengirim ulang data.'], 500);
    }
}
