<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\DownPayment;
// use App\Models\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DpPasienController extends Controller
{
    public function index()
    {
        // Data for form dropdowns
        $tipeKunjungan = ['All', 'RAWAT JALAN', 'RAWAT INAP', 'IGD'];
        $statusKunjungan = ['All', 'Aktif', 'Tutup Kunjungan'];

        // Default date values for the form
        $periodeAwalInput = Carbon::now()->format('Y-m-d');
        $periodeAkhirInput = Carbon::now()->format('Y-m-d');

        return view('pages.simrs.keuangan.kasir.laporan.dp-pasien.index', compact(
            'tipeKunjungan',
            'statusKunjungan',
            'periodeAwalInput',
            'periodeAkhirInput'
        ));
    }

    /**
     * Generate the HTML report for the popup window.
     */
    public function report(Request $request)
    {
        $query = DownPayment::with([
            'bilingan.registration.patient',
            'bilingan.registration.departement',
        ]);

        // Filter tanggal di tabel utama (down_payment)
        if ($request->filled('periode_awal') && $request->filled('periode_akhir')) {
            $start = Carbon::parse($request->periode_awal)->startOfDay();
            $end = Carbon::parse($request->periode_akhir)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // =================================================================
        // SEMUA FILTER UNTUK RELASI DIGABUNGKAN DI SINI
        // =================================================================
        $query->whereHas('bilingan.registration', function ($registrationQuery) use ($request) {

            // Filter langsung di tabel 'registrations'
            if ($request->filled('tipe_kunjungan') && $request->tipe_kunjungan != 'All') {
                $tipeKunjunganDbFormat = Str::slug($request->tipe_kunjungan);
                $registrationQuery->where('registration_type', $tipeKunjunganDbFormat);
            }
            if ($request->filled('status_kunjungan') && $request->status_kunjungan != 'All') {
                $statusDbFormat = Str::snake($request->status_kunjungan);
                $registrationQuery->where('status', $statusDbFormat);
            }
            if ($request->filled('no_registrasi')) {
                $registrationQuery->where('registration_number', $request->no_registrasi);
            }

            // Filter di tabel 'patient' yang berelasi dengan 'registration'
            if ($request->filled('no_rm') || $request->filled('nama_pasien')) {
                $registrationQuery->whereHas('patient', function ($patientQuery) use ($request) {
                    if ($request->filled('no_rm')) {
                        $patientQuery->where('medical_record_number', $request->no_rm);
                    }
                    if ($request->filled('nama_pasien')) {
                        $patientQuery->where('name', 'LIKE', '%' . $request->nama_pasien . '%');
                    }
                });
            }
        });

        $hasilLaporan = $query->latest('down_payment.created_at')->get(); // Beri alias nama tabel untuk menghindari ambiguitas
        $filters = $request->all();

        return view('pages.simrs.keuangan.kasir.laporan.dp-pasien.report', compact('hasilLaporan', 'filters'));
    }
}
