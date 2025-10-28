<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\MaterialApproval; // <-- Gunakan model ini
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectBuildItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Sumber data utama sekarang adalah MaterialApproval yang sudah 'Approved'
            $data = MaterialApproval::with(['submitter', 'reviewer', 'document'])
                ->where('status', 'Approved');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // Tombol untuk melihat detail approval di popup window
                    // Kita bisa menggunakan kembali halaman review sebagai halaman detail
                    return '<a href="' . route('material-approvals.review', $row->id) . '" target="_blank" class="btn btn-info btn-sm">Lihat Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('app-type.rs.project-build-items.index');
    }
}
