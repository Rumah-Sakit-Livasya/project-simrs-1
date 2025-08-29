<?php

// app/Http/Controllers/Api/InterventionController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $data = Intervention::query();

        // Filter berdasarkan tipe rawat (rawat-jalan / rawat-inap)
        // if ($request->has('tipe_rawat')) {
        //     $data->where('tipe_rawat', $request->tipe_rawat);
        // }

        // Filter berdasarkan query pencarian dari modal
        if ($request->filled('search_query')) {
            $data->where('name', 'like', '%' . $request->search_query . '%');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn">Edit</a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn">Hapus</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tipe_rawat' => 'required|string|in:rawat-jalan,rawat-inap',
        ]);
        Intervention::create($request->all());
        return response()->json(['success' => 'Intervensi berhasil dibuat.']);
    }

    public function edit($id)
    {
        $intervention = Intervention::findOrFail($id);
        return response()->json($intervention);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tipe_rawat' => 'required|string|in:rawat-jalan,rawat-inap',
        ]);
        $intervention = Intervention::findOrFail($id);
        $intervention->update($request->all());
        return response()->json(['success' => 'Intervensi berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        Intervention::findOrFail($id)->delete();
        return response()->json(['success' => 'Intervensi berhasil dihapus.']);
    }
}
