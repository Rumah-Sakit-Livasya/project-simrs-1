<?php

namespace App\Http\Controllers;

use App\Models\WarehouseZatAktif;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseZatAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.master-data.zat-aktif");
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $data = WarehouseZatAktif::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse.master-data.zat-aktif.show', $row->id);
                $deleteUrl = route('warehouse.master-data.zat-aktif.destroy', $row->id);
                $actionBtn = '<div class="d-flex justify-content-center">';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit-btn" data-url="' . $editUrl . '"><i class="fal fa-pencil"></i> Edit</a> ';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-url="' . $deleteUrl . '"><i class="fal fa-trash"></i> Hapus</a>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:warehouse_zat_aktif,kode',
            'aktif' => 'required|boolean',
        ]);

        try {
            WarehouseZatAktif::create($request->all());
            return response()->json(['success' => true, 'message' => 'Zat Aktif berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $zatAktif = WarehouseZatAktif::find($id);
        if (!$zatAktif) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $zatAktif]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:warehouse_zat_aktif,kode,' . $id,
            'aktif' => 'required|boolean',
        ]);

        try {
            $zatAktif = WarehouseZatAktif::findOrFail($id);
            $zatAktif->update($request->all());
            return response()->json(['success' => true, 'message' => 'Zat Aktif berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            WarehouseZatAktif::destroy($id);
            return response()->json(['success' => true, 'message' => 'Zat Aktif berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
