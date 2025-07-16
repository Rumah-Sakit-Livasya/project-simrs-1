<?php

namespace App\Http\Controllers\SIMRS\IGD;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IGDController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::query()
            ->where('registration_type', 'igd');

        $hasFilter = false;

        $simpleFilters = [
            'registration_number' => $request->registration_number,
        ];

        // Filter langsung
        foreach ($simpleFilters as $column => $value) {
            if (!empty($value)) {
                $query->where($column, 'like', '%' . $value . '%');
                $hasFilter = true;
            }
        }

        // Filter berdasarkan relasi patient
        if (!empty($request->medical_record_number)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $hasFilter = true;
        }

        // Filter berdasarkan nama pasien (relasi)
        if (!empty($request->name)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $hasFilter = true;
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
                $hasFilter = true;
            }
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === 'aktif' ? 'aktif' : 'tutup_kunjungan');
            $hasFilter = true;
        }

        // Default: jika tidak ada filter aktif, ambil data hari ini
        if (!$hasFilter) {
            $today = now()->format('Y-m-d');
            $query->whereBetween('registration_date', [
                $today . ' 00:00:00',
                $today . ' 23:59:59',
            ]);
        }

        $registrations = $query->orderBy('date', 'asc')->get();

        return view('pages.simrs.igd.daftar-pasien', [
            'registrations' => $registrations,
        ]);
    }

    function reprotIGD()
    {
        $doctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'UGD');
        })->get();

        $penjamin = Penjamin::all();

        return view('pages.simrs.igd.report-igd', compact('doctors', 'penjamin'));
    }

    public function getDataLaporan(Request $request)
    {
        try {
            $query = Registration::query()
                ->where('registration_type', 'igd');
            // ->where('status', 'tutup_kunjungan');

            if ($request->filled('doctor_id')) {
                $query->where('doctor_id', $request->doctor_id);
            }

            if ($request->filled('penjamin_id')) {
                $query->whereHas('penjamin', function ($q) use ($request) {
                    $q->where('id', $request->penjamin_id);
                });
            }

            if ($request->filled('awal_periode') && $request->filled('akhir_periode')) {
                $awalPeriode = Carbon::parse($request->awal_periode)->startOfDay();
                $akhirPeriode = Carbon::parse($request->akhir_periode)->endOfDay();
                $query->whereBetween('registration_date', [$awalPeriode, $akhirPeriode]);
            }

            $registrations = $query->get();

            return response()->json([
                'success' => true,
                'data' => $registrations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the report for IGD registrations.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showLaporan(Request $request)
    {
        $from = $request->input('awal_periode');
        $to = $request->input('akhir_periode');
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        if ($from->greaterThan($to)) {
            return redirect()->back()->withErrors(['error' => 'Tanggal awal tidak boleh lebih besar dari tanggal akhir']);
        }

        $penjaminId = $request->input('penjamin_id');
        $dokterId = $request->input('doctor_id');

        $penjaminName = $penjaminId ? Penjamin::find($penjaminId)?->nama_perusahaan : 'Semua Penjamin';
        $dokterName = $dokterId ? Doctor::with('employee')->find($dokterId)?->employee->fullname : 'Semua Dokter';

        // Query data pasien
        $pasien = Registration::with(['doctor', 'penjamin', 'patient'])
            ->where('registration_type', 'igd')
            ->whereBetween('registration_date', [$from, $to])
            ->when($dokterId, fn($q) => $q->where('doctor_id', $dokterId))
            ->when($penjaminId, fn($q) => $q->where('penjamin_id', $penjaminId))
            ->get()
            ->map(function ($item) {
                $firstVisit = Registration::where('patient_id', $item->patient_id)
                    ->orderBy('registration_date', 'asc')
                    ->first();

                $item->is_new = $firstVisit && $firstVisit->id === $item->id;
                return $item;
            });


        return view('pages.simrs.igd.partials.print-report', compact('pasien', 'from', 'to', 'penjaminId', 'dokterId', 'dokterName', 'penjaminName'));
    }
}
