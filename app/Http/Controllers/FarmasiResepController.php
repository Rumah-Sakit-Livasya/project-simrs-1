<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\FarmasiResepElektronik;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FarmasiResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.farmasi.transaksi-resep.index", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $default_apotek = WarehouseMasterGudang::select('id')->where('apotek_default', 1)->first();
        $obats = null;
        if (isset($default_apotek)) {
            $query = WarehouseBarangFarmasi::query()->with(["stored_items"]);
            $query->whereHas("stored_items", function ($q) use ($default_apotek) {
                $q->where("gudang_id", $default_apotek->id);
            });

            $obats = $query->get();
        }
        return view("pages.simrs.farmasi.transaksi-resep.resep", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get(),
            'default_apotek' => $default_apotek,
            'obats' => $obats
        ]);
    }

    public function popupPilihPasien(Request $request, $poli)
    {
        $query = Registration::query()->with(['patient', 'departement', 'penjamin', 'doctor', 'doctor.employee']);
        $filters = ['registration_number'];
        $filterApplied = false;

        // active only
        $query->where('status', 'aktif');

        if ($poli == 'rajal') {
            $query->where('registration_type', 'rawat-jalan');
        } elseif ($poli == 'ranap') {
            $query->where('registration_type', 'rawat-inap');
        }
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registrations = $query->orderBy('registration_date', 'asc')->get();
        } else {
            // Return empty collection if no filters applied
            $registrations = collect();
        }

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-pasien', compact("registrations", "poli"));
    }

    public function popupPilihDokter()
    {
        $dokters = Doctor::with(["employee"])->get();

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-dokter', compact("dokters"));
    }

    public function popupResepElektronik(Request $request)
    {
        $query = FarmasiResepElektronik::query()->with(["registration", "registration.patient", "registration.doctor", "registration.penjamin", "registration.departement", "registration.doctor.employee"]);
        $filterApplied = false;

        // Filter by date range
        if ($request->filled('tanggal')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('registration_number')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $res = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return today's data if no filter is applied
            $res = FarmasiResepElektronik::with(["registration", "registration.patient"])->whereDate('created_at', Carbon::today())->orderBy('created_at', 'asc')->get();
        }

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-elektronik', compact('res'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiResep $farmasiResep)
    {
        //
    }
}
