<?php

namespace App\Http\Controllers;

use App\Models\MakananGizi;
use Illuminate\Http\Request;

class MakananGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MakananGizi::query();
        $filterApplied = false;

        if ($request->filled('nama_makanan')) {
            $query->where("nama", "like", "%" . $request->get("nama_makanan") . "%");
            $filterApplied = true;
        }

        if ($filterApplied) {
            $result = $query->get();
        } else {
            $result = MakananGizi::all();
        }

        return view('pages.simrs.gizi.makanan', [
            'foods' => $result
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
            'harga' => 'required|integer'
        ]);

        MakananGizi::create($validatedData);
        return redirect()->back()->with('success', 'Makanan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MakananGizi $makananGizi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakananGizi $makananGizi)
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
            'harga' => 'required|integer'
        ]);

        try {
            $kategori = MakananGizi::findOrFail($id);
            $kategori->update($validatedData);
            return redirect()->back()->with('success', 'Makanan berhasil diedit!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakananGizi $makananGizi, $id)
    {
        try {
            MakananGizi::destroy($id);
            return response()->json([
                'success'=> true,
                'message'=> 'Makanan berhasil dihapus!'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> $e->getMessage()
            ]);
        }
    }
}
