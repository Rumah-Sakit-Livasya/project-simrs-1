<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InternalVehicle;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class VehicleLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data log kendaraan, urutkan terbaru paling atas (descending by created_at)
        $query = VehicleLog::with(['internal_vehicle', 'driver.employee'])->orderBy('created_at', 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)->make(true);
        }

        return response()->json($query->get());
    }

    // Endpoint khusus untuk mendapatkan Odometer Terakhir
    public function getLastOdometer(InternalVehicle $vehicle)
    {
        $lastLog = VehicleLog::where('internal_vehicle_id', $vehicle->id)
            ->where('status', 'Selesai')
            ->orderBy('end_datetime', 'desc')
            ->first();

        $odometer = $lastLog ? $lastLog->end_odometer : 0; // Default 0 jika belum pernah ada log

        return response()->json(['last_odometer' => $odometer]);
    }

    public function store(Request $request) // Proses Peminjaman
    {
        $validator = Validator::make($request->all(), [
            'internal_vehicle_id' => 'required|exists:internal_vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'start_datetime' => 'required|date',
            'start_odometer' => 'required|integer',
            'destination' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Validasi tambahan: Pastikan kendaraan tidak sedang digunakan
        $isVehicleInUse = VehicleLog::where('internal_vehicle_id', $request->internal_vehicle_id)
            ->where('status', 'Digunakan')->exists();
        if ($isVehicleInUse) {
            return response()->json(['message' => 'Kendaraan ini sedang digunakan dan belum dikembalikan!'], 409); // 409 Conflict
        }

        $isDriverInUse = VehicleLog::where('driver_id', $request->driver_id)
            ->where('status', 'Digunakan')->exists();
        if ($isDriverInUse) {
            return response()->json(['message' => 'Pengemudi ini sedang dalam perjalanan lain!'], 409);
        }

        $log = VehicleLog::create($request->all() + ['status' => 'Digunakan']);
        // Tidak perlu update current_km di proses peminjaman, hanya saat pengembalian
        return response()->json(['message' => 'Peminjaman kendaraan berhasil dicatat!', 'data' => $log], 201);
    }

    public function show(VehicleLog $vehicleLog)
    {
        return response()->json(['data' => $vehicleLog->load(['internal_vehicle', 'driver.employee'])]);
    }

    public function update(Request $request, VehicleLog $vehicleLog) // Proses Pengembalian
    {
        $validator = Validator::make($request->all(), [
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'end_odometer' => 'required|integer|min:' . $vehicleLog->start_odometer,
            'fuel_receipt' => 'nullable|image|max:2048', // file gambar maks 2MB
            'return_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataToUpdate = $request->only(['end_datetime', 'end_odometer', 'return_notes']);
        $dataToUpdate['status'] = 'Selesai';

        if ($request->hasFile('fuel_receipt')) {
            $path = $request->file('fuel_receipt')->store('fuel_receipts');
            $dataToUpdate['fuel_receipt_path'] = Storage::url($path);
        }

        $vehicleLog->update($dataToUpdate);

        // Update current_km pada InternalVehicle jika end_odometer lebih besar dari current_km
        $internalVehicle = $vehicleLog->vehicle; // pastikan relasi 'vehicle' mengarah ke InternalVehicle
        if ($internalVehicle && $request->has('end_odometer')) {
            if ($internalVehicle->current_km === null || $request->end_odometer > $internalVehicle->current_km) {
                $internalVehicle->current_km = $request->end_odometer;
                $internalVehicle->save();
            }
        }

        return response()->json(['message' => 'Pengembalian kendaraan berhasil dicatat!', 'data' => $vehicleLog]);
    }

    public function destroy(VehicleLog $vehicleLog)
    {
        if ($vehicleLog->fuel_receipt_path) {
            $path = str_replace('/storage', '', $vehicleLog->fuel_receipt_path);
            Storage::delete($path);
        }
        $vehicleLog->delete();
        return response()->json(['message' => 'Data log berhasil dihapus!']);
    }
}
