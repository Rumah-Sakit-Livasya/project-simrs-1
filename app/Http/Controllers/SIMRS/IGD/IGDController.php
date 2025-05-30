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
}
