<?php

namespace App\Http\Controllers;

use App\Models\LaporanInternal;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

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
            'status' => 'required|in:diproses,selesai,ditunda,ditolak',
            'keterangan' => 'required_if:status,ditunda,ditolak|nullable|string',
            'jam_masuk' => 'nullable',
            'jam_diterima' => 'nullable',
            'jam_diproses' => 'nullable',
            'jam_selesai' => 'nullable',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
        ]);

        // Handle file upload if exists
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $fileName);
            $validated['dokumentasi'] = Storage::url($path);
        }
        // return dd($validated['dokumentasi'] = Storage::url($path));

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
            ->addColumn('dokumentasi', function ($item) {
                if (!$item->dokumentasi || is_numeric($item->dokumentasi)) {
                    return '<span class="text-muted">Tidak ada</span>';
                }

                // Check if it's already a full URL
                if (filter_var($item->dokumentasi, FILTER_VALIDATE_URL)) {
                    $fileUrl = $item->dokumentasi;
                } else {
                    // Handle relative paths
                    $fileUrl = $item->dokumentasi;
                }

                return e($fileUrl);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function complete($id)
    {
        try {
            $laporan = LaporanInternal::findOrFail($id);
            $laporan->status = 'Selesai';
            $laporan->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $laporan = LaporanInternal::findOrFail($id);

        // Delete documentation file if exists
        if ($laporan->dokumentasi) {
            $filePath = str_replace('/storage', 'public', $laporan->dokumentasi);
            Storage::delete($filePath);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
