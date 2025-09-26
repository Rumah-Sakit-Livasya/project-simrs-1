<?php

namespace App\Http\Controllers;

use App\Models\KategoriGizi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriGiziController extends Controller
{
    /**
     * Menampilkan halaman utama.
     */
    public function index()
    {
        return view('pages.simrs.gizi.kategori-menu');
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function datatable(Request $request)
    {
        $query = KategoriGizi::query();

        if ($request->filled('nama_kategori')) {
            $query->where('nama', 'like', '%' . $request->nama_kategori . '%');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->aktif ?
                    '<span class="badge badge-success">Aktif</span>' :
                    '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-themed" data-toggle="modal" data-target="#editModal' . $row->id . '">
                                <i class="fal fa-pencil"></i>
                           </button>';
                $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger waves-effect waves-themed delete-btn" data-id="' . $row->id . '">
                                 <i class="fal fa-trash-alt"></i>
                              </button>';

                // Render modal edit secara dinamis
                $editModal = view('pages.simrs.gizi.partials.edit-kategori-modal', ['kategori' => $row])->render();

                return $editBtn . ' ' . $deleteBtn . $editModal;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_gizi,nama',
            'aktif' => 'required|boolean',
            'coa_pendapatan' => 'required|string|max:255',
            'coa_biaya' => 'required|string|max:255'
        ]);

        KategoriGizi::create($validatedData);
        return redirect()->route('gizi.kategori.index')->with('success', 'Kategori Gizi berhasil ditambahkan!');
    }

    /**
     * Mengupdate kategori.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_gizi,nama,' . $id,
            'aktif' => 'required|boolean',
            'coa_pendapatan' => 'required|string|max:255',
            'coa_biaya' => 'required|string|max:255'
        ]);

        try {
            $kategori = KategoriGizi::findOrFail($id);
            $kategori->update($validatedData);
            return redirect()->route('gizi.kategori.index')->with('success', 'Kategori Gizi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('gizi.kategori.index')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kategori.
     */
    public function destroy($id)
    {
        try {
            $kategori = KategoriGizi::findOrFail($id);
            // Optional: Cek relasi sebelum menghapus
            if ($kategori->menus()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus! Kategori ini masih digunakan oleh beberapa menu.'], 409);
            }
            $kategori->delete();
            return response()->json(['success' => true, 'message' => 'Kategori Gizi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
