<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\OtorisasiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OtorisasiUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user untuk pilihan di form modal
        $users = User::where('is_active', 1)->orderBy('name')->get();
        return view('pages.simrs.master-data.setup.otorisasi-user.index', compact('users'));
    }

    /**
     * Provide data for DataTables.
     */
    public function data()
    {
        $data = OtorisasiUser::with('user')->select('otorisasi_user.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'User Tidak Ditemukan';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('otorisasi-user.edit', $row->id);
                $deleteUrl = route('otorisasi-user.destroy', $row->id);

                $btn = '<a href="javascript:void(0)" data-url="' . $editUrl . '" class="btn btn-primary btn-sm edit-btn" data-toggle="tooltip" title="Edit"><i class="fal fa-pencil"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-url="' . $deleteUrl . '" class="btn btn-danger btn-sm delete-btn" data-toggle="tooltip" title="Hapus"><i class="fal fa-trash"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otorisasi_type' => 'required|string|max:255',
        ]);

        OtorisasiUser::create($request->all());

        return response()->json(['success' => 'Data Otorisasi berhasil ditambahkan.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $otorisasi = OtorisasiUser::findOrFail($id);
        return response()->json($otorisasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otorisasi_type' => 'required|string|max:255',
        ]);

        $otorisasi = OtorisasiUser::findOrFail($id);
        $otorisasi->update($request->all());

        return response()->json(['success' => 'Data Otorisasi berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        OtorisasiUser::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Otorisasi berhasil dihapus.']);
    }
}
