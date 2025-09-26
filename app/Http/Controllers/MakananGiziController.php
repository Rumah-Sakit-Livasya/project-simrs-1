<?php

namespace App\Http\Controllers;

use App\Models\MakananGizi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; // Pastikan Anda sudah install yajra/laravel-datatables-oracle

class MakananGiziController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen makanan.
     */
    public function index()
    {
        return view('pages.simrs.gizi.makanan');
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function datatable(Request $request)
    {
        $query = MakananGizi::query();

        if ($request->filled('nama_makanan')) {
            $query->where('nama', 'like', '%' . $request->nama_makanan . '%');
        }

        return DataTables::of($query)
            ->addIndexColumn() // FIX: Tambahkan baris ini untuk membuat kolom DT_RowIndex
            ->addColumn('harga', function ($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->addColumn('status', function ($row) {
                return $row->aktif ?
                    '<span class="badge badge-success">Aktif</span>' :
                    '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                // Tombol Edit
                $editBtn = '<button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-themed" data-toggle="modal" data-target="#editModal' . $row->id . '">
                                <i class="fal fa-pencil"></i>
                           </button>';

                // Tombol Hapus
                $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger waves-effect waves-themed delete-btn" data-id="' . $row->id . '">
                                 <i class="fal fa-trash-alt"></i>
                              </button>';

                // Include modal edit
                $editModal = view('pages.simrs.gizi.partials.edit-makanan-modal', ['food' => $row])->render();

                return $editBtn . ' ' . $deleteBtn . $editModal;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    /**
     * Menyimpan data makanan baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:makanan_gizi,nama',
            'harga' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
        ]);

        MakananGizi::create($validatedData);
        return redirect()->route('gizi.makanan.index')->with('success', 'Data makanan berhasil ditambahkan!');
    }

    /**
     * Mengupdate data makanan.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:makanan_gizi,nama,' . $id,
            'harga' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
        ]);

        try {
            $makanan = MakananGizi::findOrFail($id);
            $makanan->update($validatedData);
            return redirect()->route('gizi.makanan.index')->with('success', 'Data makanan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('gizi.makanan.index')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data makanan.
     */
    public function destroy($id)
    {
        try {
            MakananGizi::destroy($id);
            return response()->json(['success' => true, 'message' => 'Data makanan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
