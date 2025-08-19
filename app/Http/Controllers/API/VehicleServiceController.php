<?php
// app/Http/Controllers/API/VehicleServiceController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VehicleService;
use App\Models\WorkshopVendor;
use App\Models\InternalVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VehicleServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil data dengan relasi ke 'vehicle' dan 'reporter' untuk ditampilkan di tabel
        $data = VehicleService::with('internal_vehicle', 'reporter')->latest();

        return DataTables::of($data)->make(true);
    }

    // FUNGSI BARU: Untuk mengambil data bengkel rekanan
    public function getWorkshopVendors()
    {
        $vendors = WorkshopVendor::orderBy('name')->get(['id', 'name']);
        return response()->json($vendors);
    }

    // FUNGSI BARU: Untuk mengambil detail satu tiket servis
    public function show($id)
    {
        $service = VehicleService::with('internal_vehicle')->findOrFail($id);

        // Tambahkan last_oil_change_km dari internal_vehicle
        $last_oil_change_km = null;
        if ($service->internal_vehicle) {
            $last_oil_change_km = $service->internal_vehicle->last_oil_change_km;
        }

        // Gabungkan last_oil_change_km ke response
        $response = $service->toArray();
        $response['last_oil_change_km'] = $last_oil_change_km;

        return response()->json($response);
    }

    // FUNGSI BARU: Untuk menyimpan perubahan dari form modal
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_date' => 'required|date',
            'workshop_vendor_id' => 'nullable|exists:workshop_vendors,id',
            'work_done' => 'required|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'parts_cost' => 'nullable|numeric|min:0',
            'odometer_at_service' => 'nullable|integer|min:0',
            'status' => 'required|in:In Progress,Completed',
            'invoice' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
            // Jika ada field relasi, pastikan gunakan vehicle_service_id, bukan service_id
            'vehicle_service_id' => 'sometimes|nullable|integer|exists:vehicle_services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = VehicleService::findOrFail($id);

        // Pastikan hanya mengisi field yang benar, ganti service_id dengan vehicle_service_id jika ada
        $data = $request->except('invoice', 'service_id');
        if ($request->has('vehicle_service_id')) {
            $data['vehicle_service_id'] = $request->input('vehicle_service_id');
        }

        $service->fill($data);

        if ($request->hasFile('invoice')) {
            // Hapus file lama jika ada
            if ($service->invoice_path) {
                Storage::delete(str_replace('/storage/', '', $service->invoice_path));
            }
            $path = $request->file('invoice')->store('public/service_invoices');
            $service->invoice_path = Storage::url($path);
        }

        $service->save();

        return response()->json(['message' => 'Tiket servis berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $service = VehicleService::findOrFail($id);

        // Hapus file invoice dari storage jika ada
        if ($service->invoice_path) {
            Storage::delete(str_replace('/storage/', '', $service->invoice_path));
        }

        $service->delete();

        return response()->json(['message' => 'Tiket servis berhasil dihapus!']);
    }
}
