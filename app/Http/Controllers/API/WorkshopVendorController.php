<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WorkshopVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WorkshopVendorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WorkshopVendor::latest()->get();
            return DataTables::of($data)->make(true);
        }
        return response()->json(['data' => WorkshopVendor::latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vendor = WorkshopVendor::create($request->all());
        return response()->json(['message' => 'Vendor berhasil ditambahkan!', 'data' => $vendor], 201);
    }

    public function show(WorkshopVendor $workshopVendor)
    {
        return response()->json(['data' => $workshopVendor]);
    }

    public function update(Request $request, WorkshopVendor $workshopVendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $workshopVendor->update($request->all());
        return response()->json(['message' => 'Data vendor berhasil diperbarui!', 'data' => $workshopVendor]);
    }

    public function destroy(WorkshopVendor $workshopVendor)
    {
        $workshopVendor->delete();
        return response()->json(['message' => 'Data vendor berhasil dihapus!']);
    }
}
