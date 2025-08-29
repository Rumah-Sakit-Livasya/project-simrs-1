<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InspectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InspectionItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InspectionItem::latest()->get();
            return DataTables::of($data)->make(true);
        }
        return response()->json(['data' => InspectionItem::latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inspection_items,name',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $item = InspectionItem::create($request->all());
        return response()->json(['message' => 'Item pemeriksaan berhasil ditambahkan!', 'data' => $item], 201);
    }

    public function show(InspectionItem $inspectionItem)
    {
        return response()->json(['data' => $inspectionItem]);
    }

    public function update(Request $request, InspectionItem $inspectionItem)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inspection_items,name,' . $inspectionItem->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $inspectionItem->update($request->all());
        return response()->json(['message' => 'Item pemeriksaan berhasil diperbarui!', 'data' => $inspectionItem]);
    }

    public function destroy(InspectionItem $inspectionItem)
    {
        $inspectionItem->delete();
        return response()->json(['message' => 'Item pemeriksaan berhasil dihapus!']);
    }
}
