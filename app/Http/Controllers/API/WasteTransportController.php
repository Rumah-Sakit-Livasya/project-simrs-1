<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteTransport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WasteTransportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WasteTransport::with(['wasteCategory', 'vehicle'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm editTransport">Edit</a> ';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteTransport">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        WasteTransport::updateOrCreate(
            ['id' => $request->id],
            [
                'date' => $request->date,
                'waste_category_id' => $request->waste_category_id,
                'vehicle_id' => $request->vehicle_id,
                'volume' => $request->volume,
                'pic_vendor' => $request->pic_vendor,
            ]
        );
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $transport = WasteTransport::find($id);
        return response()->json($transport);
    }

    public function destroy($id)
    {
        WasteTransport::find($id)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }
}
