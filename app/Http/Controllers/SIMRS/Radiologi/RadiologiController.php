<?php

namespace App\Http\Controllers\SIMRS\Radiologi;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OrderParameterRadiologi;
use App\Models\OrderRadiologi;
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



        return view('pages.simrs.radiologi.partials.edit-order', [
            'order' => $order,
            'parametersInCategory' => $parameterCategories,
            'radiografers' => $radiografers
        ]);
    }

    public function templateHasil(Request $request)
    {
        $query = TemplateHasilRadiologi::query();
        $filters = ['judul', 'template'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $template = $query->get();
        } else {
            $template = TemplateHasilRadiologi::all();
        }

        // return dd(TemplateHasilRadiologi::all());

        return view('pages.simrs.radiologi.template-hasil', [
            'templates' => $template
        ]);
    }

    public function tambahTemplateHasil(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required',
            'template' => 'required',
        ]);

        try {
            TemplateHasilRadiologi::create($validatedData);
        } catch (\Exception $e) {
            return response("<script> alert('Error: " . $e->getMessage() . "'); </script>");
        }

        return back();
    }
}
