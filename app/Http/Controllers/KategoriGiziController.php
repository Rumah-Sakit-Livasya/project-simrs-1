<?php

namespace App\Http\Controllers;

use App\Models\KategoriGizi;
use Illuminate\Http\Request;

class KategoriGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KategoriGizi::query();
        $filterApplied = false;

        if ($request->filled('nama_kategori')) {
            $query->where("nama", "like", "%" . $request->get("nama_kategori") . "%");
            $filterApplied = true;
        }

        if ($filterApplied) {
            $result = $query->get();
        } else {
            $result = KategoriGizi::all();
        }

        return view('pages.simrs.gizi.kategori-menu', [
            'categories' => $result
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'aktif' => 'required|boolean',
            'coa_pendapatan' => 'required|string|max:255',
            'coa_biaya' => 'required|string|max:255'
        ]);

        KategoriGizi::create($validatedData);
        return redirect()->back()->with('success', 'Kategori Gizi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriGizi $kategoriGizi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriGizi $kategoriGizi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'aktif' => 'required|boolean',
            'coa_pendapatan' => 'required|string|max:255',
            'coa_biaya' => 'required|string|max:255'
        ]);

        try {
            $kategori = KategoriGizi::findOrFail($id);
            $kategori->update($validatedData);
            return redirect()->back()->with('success', 'Kategori Gizi berhasil diedit!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriGizi $kategoriGizi, $id)
    {
        try {
            KategoriGizi::destroy($id);
            return response()->json([
                'success'=> true,
                'message'=> 'Kategori Gizi berhasil dihapus!'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> $e->getMessage()
            ]);
        }
    }
}
