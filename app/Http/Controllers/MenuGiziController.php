<?php

namespace App\Http\Controllers;

use App\Models\KategoriGizi;
use App\Models\MakananGizi;
use App\Models\MakananMenuGizi;
use App\Models\MenuGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MenuGiziController extends Controller
{
    /**
     * Menampilkan halaman utama.
     */
    public function index()
    {
        // Data ini dibutuhkan untuk mengisi pilihan di modal Tambah dan Edit.
        $foods = MakananGizi::where('aktif', true)->orderBy('nama')->get();
        $categories = KategoriGizi::orderBy('nama')->get();

        return view('pages.simrs.gizi.menu', [
            'foods' => $foods,
            'categories' => $categories
        ]);
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function datatable(Request $request)
    {
        // ... kode query ...
        $query = MenuGizi::with(['category', 'makanan_menu.makanan']);

        if ($request->filled('nama_menu')) {
            $query->where('nama', 'like', '%' . $request->nama_menu . '%');
        }


        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('detail', function ($row) {
                return '';
            })
            // ... addColumn lainnya ...
            ->addColumn('kategori', function ($row) {
                return $row->category->nama ?? 'N/A';
            })
            ->addColumn('harga', function ($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->addColumn('status', function ($row) {
                return $row->aktif ?
                    '<span class="badge badge-success">Aktif</span>' :
                    '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                // ... logika tombol ...
                $editBtn = '<button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-themed" data-toggle="modal" data-target="#editModal' . $row->id . '">
                                <i class="fal fa-pencil"></i>
                           </button>';
                $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger waves-effect waves-themed delete-btn" data-id="' . $row->id . '">
                                 <i class="fal fa-trash-alt"></i>
                              </button>';

                $editModal = view('pages.simrs.gizi.partials.edit-menu-modal', [
                    'menu' => $row,
                    'foods' => MakananGizi::where('aktif', true)->orderBy('nama')->get(),
                    'categories' => KategoriGizi::orderBy('nama')->get()
                ])->render();

                return $editBtn . ' ' . $deleteBtn . $editModal;
            })
            ->addColumn('detail_makanan', function ($row) {
                $row->load('makanan_menu.makanan');
                return view('pages.simrs.gizi.partials.detail-menu-gizi', ['menu' => $row])->render();
            })
            // --- FIX DI SINI ---
            // Tambahkan 'detail_makanan' ke dalam rawColumns
            ->rawColumns(['status', 'action', 'detail_makanan'])
            ->make(true);
    }

    /**
     * Menyimpan menu baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:menu_gizi,nama',
            'harga' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
            'kategori_id' => 'required|exists:kategori_gizi,id',
            'foods' => 'required|array|min:1',
            'foods.*.id' => 'required|exists:makanan_gizi,id',
            'foods.*.status' => 'required|boolean'
        ]);

        DB::transaction(function () use ($validated) {
            $menu = MenuGizi::create([
                'nama' => $validated['nama'],
                'harga' => $validated['harga'],
                'aktif' => $validated['aktif'],
                'kategori_id' => $validated['kategori_id'],
            ]);

            foreach ($validated['foods'] as $food) {
                $menu->makanan_menu()->create([
                    'makanan_id' => $food['id'],
                    'aktif' => $food['status'],
                ]);
            }
        });

        return redirect()->route('gizi.menu.index')->with('success', 'Menu gizi berhasil ditambahkan.');
    }

    /**
     * Mengupdate menu.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:menu_gizi,nama,' . $id,
            'harga' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
            'kategori_id' => 'required|exists:kategori_gizi,id',
            'foods' => 'required|array|min:1',
            'foods.*.id' => 'required|exists:makanan_gizi,id',
            'foods.*.status' => 'required|boolean'
        ]);

        $menu = MenuGizi::findOrFail($id);

        DB::transaction(function () use ($validated, $menu) {
            $menu->update([
                'nama' => $validated['nama'],
                'harga' => $validated['harga'],
                'aktif' => $validated['aktif'],
                'kategori_id' => $validated['kategori_id'],
            ]);

            // Hapus relasi lama dan buat yang baru (sync)
            $menu->makanan_menu()->delete();
            foreach ($validated['foods'] as $food) {
                $menu->makanan_menu()->create([
                    'makanan_id' => $food['id'],
                    'aktif' => $food['status'],
                ]);
            }
        });

        return redirect()->route('gizi.menu.index')->with('success', 'Menu gizi berhasil diperbarui.');
    }


    /**
     * Menghapus menu.
     */
    public function destroy($id)
    {
        try {
            // Transaction untuk memastikan semua data terkait terhapus
            DB::transaction(function () use ($id) {
                $menu = MenuGizi::findOrFail($id);
                $menu->makanan_menu()->delete();
                $menu->delete();
            });
            return response()->json(['success' => true, 'message' => 'Menu gizi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
