<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager load relasi employee untuk ditampilkan di tabel
            $data = Driver::with('employee')->latest()->get();
            return DataTables::of($data)
                ->editColumn('employee.name', function ($driver) {
                    return $driver->employee ? $driver->employee->name : 'N/A';
                })
                ->make(true);
        }
        return response()->json(['data' => Driver::with('employee')->latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id|unique:drivers,employee_id',
            'no_sim' => 'required|string|unique:drivers,no_sim',
            'masa_berlaku_sim' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $driver = Driver::create($request->all());
        return response()->json(['message' => 'Data pengemudi berhasil ditambahkan!', 'data' => $driver], 201);
    }

    public function show(Driver $driver)
    {
        // Muat juga data employee saat mengambil satu record
        return response()->json(['data' => $driver->load('employee')]);
    }

    public function update(Request $request, Driver $driver)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id|unique:drivers,employee_id,' . $driver->id,
            'no_sim' => 'required|string|unique:drivers,no_sim,' . $driver->id,
            'masa_berlaku_sim' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $driver->update($request->all());
        return response()->json(['message' => 'Data pengemudi berhasil diperbarui!', 'data' => $driver]);
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return response()->json(['message' => 'Data pengemudi berhasil dihapus!']);
    }
}
