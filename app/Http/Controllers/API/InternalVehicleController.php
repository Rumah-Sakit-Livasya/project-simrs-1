<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternalVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InternalVehicleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InternalVehicle::latest()->get();
            return DataTables::of($data)->make(true);
        }
        // Fallback untuk non-ajax request jika diperlukan
        return response()->json(['data' => InternalVehicle::latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'license_plate' => 'required|string|unique:internal_vehicles,license_plate',
            'brand_model' => 'required|string',
            'model_year' => 'required|digits:4',
            'tax_due_date' => 'required|date',
            'stnk_due_date' => 'required|date',
            'service_schedule_km' => 'nullable|integer',
            'service_schedule_months' => 'nullable|integer',
            'current_km' => 'nullable|integer|min:0',
            'last_oil_change_km' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vehicle = InternalVehicle::create($request->all());
        return response()->json(['message' => 'Kendaraan berhasil ditambahkan!', 'data' => $vehicle], 201);
    }

    public function show(InternalVehicle $internalVehicle)
    {
        return response()->json(['data' => $internalVehicle]);
    }

    public function update(Request $request, InternalVehicle $internalVehicle)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'license_plate' => 'required|string|unique:internal_vehicles,license_plate,' . $internalVehicle->id,
            'brand_model' => 'required|string',
            'model_year' => 'required|digits:4',
            'tax_due_date' => 'required|date',
            'stnk_due_date' => 'required|date',
            'service_schedule_km' => 'nullable|integer',
            'service_schedule_months' => 'nullable|integer',
            'current_km' => 'nullable|integer|min:0',
            'last_oil_change_km' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $internalVehicle->update($request->all());
        return response()->json(['message' => 'Data kendaraan berhasil diperbarui!', 'data' => $internalVehicle]);
    }

    public function destroy(InternalVehicle $internalVehicle)
    {
        $internalVehicle->delete();
        return response()->json(['message' => 'Kendaraan berhasil dihapus!']);
    }
}
