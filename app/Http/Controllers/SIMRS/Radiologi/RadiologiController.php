<?php

namespace App\Http\Controllers\SIMRS\Radiologi;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OrderParameterRadiologi;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\GrupParameterRadiologi;
use App\Models\SIMRS\KategoriRadiologi;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use App\Models\TemplateHasilRadiologi;
use Illuminate\Http\Request;

class RadiologiController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderRadiologi::query()->with('registration');
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

        return view('pages.simrs.radiologi.list-order', [
            'orders' => $order
        ]);
    }

    public function notaOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.nota-order', [
            'order' => $order
        ]);
    }

    public function hasilOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.hasil-order', [
            'order' => $order
        ]);
    }

    public function labelOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.label-order', [
            'order' => $order
        ]);
    }

    public function editHasilParameter($id)
    {
        $parameter = OrderParameterRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.edit-hasil-parameter', [
            'parameter' => $parameter
        ]);
    }

    public function editOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        // organizations table, id "Radiologi" = 24
        $radiografers = Employee::where(['organization_id' => 24])->get();
        $parameters =  $order->order_parameter_radiologi;
        $parameterCategories = [];

        foreach ($parameters as $parameter) {
            $category = $parameter->parameter_radiologi->kategori_radiologi->nama_kategori;
            $category = $parameter['parameter_radiologi']['kategori_radiologi']['nama_kategori'];
            if (!isset($parameterCategories[$category])) {
                $parameterCategories[$category] = [];
            }
            $parameterCategories[$category][] = $parameter;
        }

        $template = TemplateHasilRadiologi::all();


        return view('pages.simrs.radiologi.partials.edit-order', [
            'order' => $order,
            'parametersInCategory' => $parameterCategories,
            'radiografers' => $radiografers,
            'templates' => $template
        ]);
    }

    public function templateHasil(Request $request)
    {
        $query = TemplateHasilRadiologi::query();
        $filters = ['cari-judul', 'cari-template'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where(str_replace("cari-", "", $filter), 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $template = $query->get();
        } else {
            $template = TemplateHasilRadiologi::all();
        }

        return view('pages.simrs.radiologi.template-hasil', [
            'templates' => $template
        ]);
    }

    public function simpanTemplateHasil(Request $request, $id)
    {
        try {
            if (!isset($id) || $id == 0) {
                // insert
                $validatedData = $request->validate([
                    'judul' => 'required',
                    'template' => 'required',
                ]);
                TemplateHasilRadiologi::create([
                    'judul' => $validatedData['judul'],
                    'template' => $validatedData['template']
                ]);
            } else {
                // update
                $validatedData = $request->validate([
                    'judul' . $id => 'required',
                    'template' . $id => 'required'
                ]);
                $template = TemplateHasilRadiologi::findOrFail($id);
                $template->update([
                    'judul' => $validatedData['judul' . $id],
                    'template' => $validatedData['template' . $id]
                ]);
            }
        } catch (\Exception $e) {
            return response("<script> alert('Error: " . $e->getMessage() . "'); </script>");
        }

        // success
        return back();
    }

    public function deleteTemplate($id)
    {
        try {
            $template = TemplateHasilRadiologi::findOrFail($id);
            $template->delete();
        } catch (\Exception $e) {
            return response("<script> alert('Error: " . $e->getMessage() . "'); </script>");
        }

        return back();
    }

    public function simulasiHarga()
    {
        return view('pages.simrs.radiologi.simulasi-harga', [
            'radiology_categories' => KategoriRadiologi::all(),
            'tarifs' => TarifParameterRadiologi::all(),
        ]);
    }

    public function report(Request $request)
    {
        $groupParameter = GrupParameterRadiologi::all();
        $radiografer = Employee::where('organization_id', 24)->get();
        $penjamin = Penjamin::all();

        return view('pages.simrs.radiologi.laporan', [
            'groupParameters' => $groupParameter,
            'radiografers' => $radiografer,
            'penjamins' => $penjamin
        ]);
    }

    public function reportView($fromDate, $endDate, $tipe_rawat, $group_parameter, $penjamin, $radiografer)
    {

        $query = OrderParameterRadiologi::query()->with(['order_radiologi', 'registration']);
        $query->whereHas('order_radiologi', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_radiologi.order_date', [$fromDate, $endDate]);
        });

        $query->whereHas('registration', function ($q) use ($tipe_rawat) {
            switch ($tipe_rawat) {
                case 'rajal':
                    $q->where('registration_type', 'rawat-jalan');
                    break;
                case 'ranap':
                    $q->where('registration_type', 'rawat-inap');
                    break;
                case 'otc':
                    $q->where('registration_type', 'odc');
                    break;
            }
        });

        if ($group_parameter && $group_parameter != '-') {
            $query->whereHas('grup_parameter_radiologi', function ($q) use ($group_parameter) {
                $q->where('id', 'like', '%' . $group_parameter . '%');
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        if ($radiografer && $radiografer != '-') {
            $query->whereHas('employees', function ($q) use ($radiografer) {
                $q->where('id', 'like', '%' . $radiografer . '%');
            });
        }

        $orders = $query->get();


        return view('pages.simrs.radiologi.partials.laporan-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }
}
