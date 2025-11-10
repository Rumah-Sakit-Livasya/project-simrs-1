<?php

namespace App\Http\Controllers\SIMRS\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SIMRS\Departement;
use App\Models\Employee;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use Illuminate\Support\Facades\DB;

class LaporanPoliklinikController extends Controller
{
    /**
     * Menampilkan halaman form pencarian laporan.
     */
    public function index()
    {
        // Ganti 'nama_perusahaan' dengan nama kolom yang benar di model Penjamin Anda, misal 'name'
        $poliklinik = Departement::orderBy('name', 'asc')->get();
        $dokter = Employee::where('is_doctor', 1)->where('is_active', 1)->orderBy('fullname', 'asc')->get();
        $penjamin = Penjamin::orderBy('nama_perusahaan', 'asc')->get(); // Pastikan kolom 'name' ada, atau ganti

        return view('pages.simrs.laporan.poliklinik.index', compact('poliklinik', 'dokter', 'penjamin'));
    }

    /**
     * Menampilkan hasil laporan berdasarkan filter.
     */
    public function show(Request $request)
    {
        $request->validate([
            'stgl1' => 'required|date_format:d-m-Y',
            'stgl2' => 'required|date_format:d-m-Y',
        ]);

        $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->stgl1)->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->stgl2)->endOfDay();

        $query = Registration::with([
            'patient.registration',
            'departement',
            'doctor.employee',
            'penjamin',
            'user'
        ])
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->where('registration_type', 'rawat-jalan');

        if ($request->filled('did')) {
            $query->where('departement_id', $request->did);
        }
        if ($request->filled('pid')) {
            $query->where('doctor_id', $request->pid);
        }
        if ($request->filled('insid')) {
            $query->where('penjamin_id', $request->insid);
        }

        $results = $query->orderBy('registration_date', 'asc')->get();
        $filter = $request->all();

        $poliklinik = Departement::find($request->did);
        $dokter = Employee::find($request->pid);
        $penjamin = Penjamin::find($request->insid);

        // =========================================================
        // LOGIKA EXPORT EXCEL
        // =========================================================
        if ($request->has('export') && $request->export === 'xls') {
            $fileName = 'laporan-pasien-poliklinik-' . \Carbon\Carbon::createFromFormat('d-m-Y', $request->stgl1)->format('Ymd') . '-' . \Carbon\Carbon::createFromFormat('d-m-Y', $request->stgl2)->format('Ymd') . '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\LaporanPoliklinikExport($results, $filter, $poliklinik, $dokter, $penjamin),
                $fileName
            );
        }
        // =========================================================

        return view('pages.simrs.laporan.poliklinik.show', compact('results', 'filter', 'poliklinik', 'dokter', 'penjamin'));
    }
}
