<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Exports\LaboratoriumTarifExport;
use App\Http\Controllers\Controller;
use App\Imports\LaboratoriumTarifImport;
use App\Models\Employee;
use App\Models\OrderParameterLaboratorium;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaboratoriumController extends Controller
{
    public function order()
    {
        $laboratoriumDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%laboratorium%');
        })->get();
        return view('pages.simrs.laboratorium.order', [
            'laboratoriumDoctors' => $laboratoriumDoctors,
            'penjamins' => Penjamin::all(),
            'kelas_rawats' => KelasRawat::all(),
            'laboratorium_categories' => KategoriLaboratorium::all(),
            'tarifs' => TarifParameterLaboratorium::all(),
        ]);
    }

    public function notaOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        return view('pages.simrs.laboratorium.partials.nota-order', [
            'order' => $order
        ]);
    }

    public function labelOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        return view('pages.simrs.laboratorium.partials.label-order', [
            'order' => $order
        ]);
    }

    public function hasilOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        return view('pages.simrs.laboratorium.partials.hasil-order', [
            'order' => $order,
            'nilai_normals' => NilaiNormalLaboratorium::all()
        ]);
    }

    public function index(Request $request)
    {
        $query = OrderLaboratorium::query()->with(['registration', 'registration_otc', 'registration_otc.doctor']);
        $filters = ['medical_record_number', 'registration_number', 'no_order'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->order_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('order_date', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $order = $query->orderBy('order_date', 'asc')->get();
        } else {
            // Return empty collection if no filters applied
            $order = collect();
        }

        return view('pages.simrs.laboratorium.list-order', [
            'orders' => $order
        ]);
    }

    public function editOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $parameters = $order->order_parameter_laboratorium;
        $parameterCategories = [];


        foreach ($parameters as $parameter) {
            $category = $parameter->parameter_laboratorium->kategori_laboratorium->nama_kategori;
            $category = $parameter['parameter_laboratorium']['kategori_laboratorium']['nama_kategori'];
            if (!isset($parameterCategories[$category])) {
                $parameterCategories[$category] = [];
            }
            $parameterCategories[$category][] = $parameter;
        }

        return view('pages.simrs.laboratorium.partials.edit-order', [
            'order' => $order,
            'parametersInCategory' => $parameterCategories,
            'nilai_normals' => NilaiNormalLaboratorium::all()
        ]);
    }

    public function simulasiHarga()
    {
        return view('pages.simrs.laboratorium.simulasi-harga', [
            'laboratorium_categories' => KategoriLaboratorium::all(),
            'tarifs' => TarifParameterLaboratorium::all(),
            'group_penjamins' => GroupPenjamin::all(),
            'kelas_rawats' => KelasRawat::all()
        ]);
    }

    public function popupPilihPasien(Request $request, $poli)
    {
        $query = Registration::query()->with(['patient', 'departement']);
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

        return view('pages.simrs.laboratorium.partials.popup-pilih-pasien', compact("registrations", "poli"));
    }

    public function reportParameterView($fromDate, $endDate, $tipe_rawat, $penjamin)
    {
        $query = OrderParameterLaboratorium::query()->with(['order_laboratorium', 'registration', 'registration_otc', 'registration_otc.doctor']);
        $query->whereHas('order_laboratorium', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_laboratorium.order_date', [$fromDate, $endDate]);
        });

        if ($tipe_rawat != "otc" && $tipe_rawat != "-") {
            $query->whereHas('registration', function ($q) use ($tipe_rawat) {
                switch ($tipe_rawat) {
                    case 'rajal':
                        $q->where('registration_type', 'rawat-jalan');
                        break;
                    case 'ranap':
                        $q->where('registration_type', 'rawat-inap');
                        break;
                }
            });
        }

        if ($tipe_rawat == "otc") {
            $query->whereHas('order_laboratorium', function ($q) {
                $q->where("otc_id", "!=", null);
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        $orders = $query->get();

        // [group => [parameter => count]]
        $reports = [];

        foreach ($orders as $order) {
            $grup = $order->parameter_laboratorium->grup_parameter_laboratorium->nama_grup;

            // check if $reports has $grup
            if (!isset($reports[$grup])) {
                $reports[$grup] = [];
            }

            // check if $reports[$grup] has $parameter
            $nama_parameter = $order->parameter_laboratorium->parameter;
            if (!isset($reports[$grup][$nama_parameter])) {
                $reports[$grup][$nama_parameter] = 0;
            }

            // up the count
            $reports[$grup][$nama_parameter] += 1;
        }

        // dd($reports);

        return view('pages.simrs.laboratorium.partials.laporan-parameter-view', [
            'reports' => $reports,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }

    public function reportPatientView($fromDate, $endDate, $tipe_rawat, $penjamin, $parameter)
    {
        $query = OrderParameterLaboratorium::query()->with(['order_laboratorium', 'registration', 'registration_otc', 'registration_otc.doctor']);
        $query->whereHas('order_laboratorium', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_laboratorium.order_date', [$fromDate, $endDate]);
        });

        if ($tipe_rawat != "otc" && $tipe_rawat != "-") {
            $query->whereHas('registration', function ($q) use ($tipe_rawat) {
                switch ($tipe_rawat) {
                    case 'rajal':
                        $q->where('registration_type', 'rawat-jalan');
                        break;
                    case 'ranap':
                        $q->where('registration_type', 'rawat-inap');
                        break;
                }
            });
        }

        if ($tipe_rawat == "otc") {
            $query->whereHas('order_laboratorium', function ($q) {
                $q->where("otc_id", "!=", null);
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        if ($parameter && $parameter != '-') {
            $query->where('id', $parameter);
        }

        $orders = $query->get();

        return view('pages.simrs.laboratorium.partials.laporan-patient-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }

    public function reportParameter(Request $request)
    {
        $penjamin = Penjamin::all();
        return view('pages.simrs.laboratorium.laporan-parameter', [
            'penjamins' => $penjamin
        ]);
    }

    public function reportPatient(Request $request)
    {
        $parameters = ParameterLaboratorium::all();
        $penjamin = Penjamin::all();

        return view('pages.simrs.laboratorium.laporan-pasien', [
            'parameters' => $parameters,
            'penjamins' => $penjamin
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'grup_penjamin_id' => 'required|integer',
            'departement_id' => 'required|integer',
        ]);
        $grupPenjaminId = $request->grup_penjamin_id;
        $departementId = $request->departement_id;

        return Excel::download(new LaboratoriumTarifExport($grupPenjaminId, $departementId), 'Template-Tarif-Laboratorium.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);

        try {
            Excel::import(new LaboratoriumTarifImport, $request->file('file'));
            return back()->with('success', 'Tarif laboratorium berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
