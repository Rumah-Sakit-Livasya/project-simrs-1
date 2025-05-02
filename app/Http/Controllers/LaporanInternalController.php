<?php

namespace App\Http\Controllers;

use App\Models\LaporanInternal;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LaporanInternalController extends Controller
{
    public function index()
    {
        return view('pages.laporan-internal.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:kendala,kegiatan',
            'kegiatan' => 'required|string',
            'status' => 'required|in:Baru,Diproses,Selesai,Dipending,Ditolak',
            'keterangan' => 'required_if:status,Dipending,Ditolak|nullable|string',
            'jam_masuk' => 'nullable',
            'jam_diterima' => 'nullable',
            'jam_diproses' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);

        $laporan = LaporanInternal::create($validated);

        return response()->json([
            'message' => 'Laporan berhasil ditambahkan.',
            'data' => $laporan
        ]);
    }

    public function list(Request $request)
    {
        $query = LaporanInternal::query();

        return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <div class="btn-group">
                        <button class="btn btn-sm btn-icon btn-primary" onclick="editLaporan(' . $item->id . ')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLaporan(' . $item->id . ')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy($id)
    {
        $laporanInternal = LaporanInternal::find($id);
        $laporanInternal->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
