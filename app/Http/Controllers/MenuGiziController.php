<?php

namespace App\Http\Controllers;

use App\Models\KategoriGizi;
use App\Models\MakananGizi;
use App\Models\MakananMenuGizi;
use App\Models\MenuGizi;
use Illuminate\Http\Request;

class MenuGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MenuGizi::query();
        $filterApplied = false;

        if ($request->filled('nama_menu')) {
            $query->where("nama", "like", "%" . $request->get("nama_menu") . "%");
            $filterApplied = true;
        }

        if ($filterApplied) {
            $result = $query->get();
        } else {
            $result = MenuGizi::all();
        }

        return view('pages.simrs.gizi.menu', [
            'menus' => $result,
            'foods' => MakananGizi::all(),
            'categories' => KategoriGizi::all()
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
            'harga' => 'required|integer',
            'kategori_id' => 'required|integer',
            'foods_status' => 'required|array',
            'foods_status.*' => 'required|boolean',
            'foods_id' => 'required|array',
            'foods_id.*' => 'required|integer|exists:makanan_gizi,id',
        ]);

        $menu = MenuGizi::create($validatedData);

        foreach ($validatedData['foods_id'] as $index => $foodsId) {
            MakananMenuGizi::create([
                "menu_gizi_id" => $menu->id,
                "makanan_id" => $foodsId,
                "aktif" => isset($validatedData['foods_status'][$index]) ? $validatedData['foods_status'][$index] : false,
            ]);
        }

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuGizi $menuGizi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuGizi $menuGizi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuGizi $menuGizi, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'aktif' => 'required|boolean',
            'harga' => 'required|integer',
            'kategori_id' => 'required|integer',
            'foods_status' => 'required|array',
            'foods_status.*' => 'required|boolean',
            'foods_id' => 'required|array',
            'foods_id.*' => 'required|integer|exists:makanan_gizi,id',
        ]);

        // update menu gizi
        // where id == $id
        $menuGizi->update($validatedData);

        // delete all data from MakananMenuGizi
        // where "menu_gizi_id" == $id
        MakananMenuGizi::where('menu_gizi_id', $id)->delete();

        foreach ($validatedData['foods_id'] as $index => $foodsId) {
            MakananMenuGizi::create([
                "menu_gizi_id" => $id,
                "makanan_id" => $foodsId,
                "aktif" => isset($validatedData['foods_status'][$index]) ? $validatedData['foods_status'][$index] : false,
            ]);
        }
        return redirect()->back()->with('success', 'Menu berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuGizi $menuGizi, $id)
    {
        try {
            MenuGizi::destroy($id);

            // delete all data from MakananMenuGizi
            // where "menu_gizi_id" == $id
            MakananMenuGizi::where('menu_gizi_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
