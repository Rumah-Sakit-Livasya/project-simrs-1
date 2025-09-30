<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\TipeTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TipeTransaksiController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen Tipe Transaksi.
     */
    public function index()
    {
        return view('pages.simrs.master-data.setup.tipe-transaksi.index');
    }

    /**
     * Menyediakan data untuk DataTables (Server-Side).
     */
    public function data()
    {
        try {
            $query = TipeTransaksi::query()->orderBy('urutan', 'asc');

            return DataTables::of($query)
                ->addColumn('aksi', function ($row) {
                    return '
                        <div class="d-flex">
                            <button class="btn btn-warning btn-sm btn-edit mr-2" data-id="' . $row->id . '">
                                <i class="fal fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">
                                <i class="fal fa-trash"></i> Hapus
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error fetching Tipe Transaksi data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak dapat diambil.'], 500);
        }
    }

    /**
     * Menyimpan data Tipe Transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tipe_transaksi,name',
            'urutan' => 'required|integer|min:0',
        ]);

        try {
            TipeTransaksi::create($request->all());
            return response()->json(['success' => 'Data Tipe Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            Log::error('Error storing Tipe Transaksi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data.'], 500);
        }
    }

    /**
     * Mengambil data untuk form edit.
     */
    public function edit($id)
    {
        try {
            $tipeTransaksi = TipeTransaksi::findOrFail($id);
            return response()->json($tipeTransaksi);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    /**
     * Memperbarui data Tipe Transaksi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tipe_transaksi,name,' . $id,
            'urutan' => 'required|integer|min:0',
        ]);

        try {
            $tipeTransaksi = TipeTransaksi::findOrFail($id);
            $tipeTransaksi->update($request->all());
            return response()->json(['success' => 'Data Tipe Transaksi berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Error updating Tipe Transaksi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memperbarui data.'], 500);
        }
    }

    /**
     * Menghapus data Tipe Transaksi.
     */
    public function destroy($id)
    {
        try {
            $tipeTransaksi = TipeTransaksi::findOrFail($id);
            $tipeTransaksi->delete();
            return response()->json(['success' => 'Data Tipe Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Error deleting Tipe Transaksi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus data.'], 500);
        }
    }
}
