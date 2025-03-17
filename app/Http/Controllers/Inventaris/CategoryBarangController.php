<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\TemplateBarang;
use Illuminate\Http\Request;

class CategoryBarangController extends Controller
{
    public function index()
    {
        $categoryBarang = CategoryBarang::orderBy('created_at', 'desc')->get();
        return view('app-type.logistik.category-barang.index', compact('categoryBarang'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'category_code' => 'max:255|required',
        ]);

        try {
            $store = CategoryBarang::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCategory($id)
    {
        try {
            $room = CategoryBarang::findOrFail($id);

            return response()->json([
                'name' => $room->name,
                'category_code' => $room->category_code,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'category_code' => 'max:255|required',
        ]);

        try {
            $room = CategoryBarang::findOrFail($id);
            $room->update($validatedData);
            return response()->json(['message' => "$room->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $room = CategoryBarang::find($id);
            $room->delete();
            return response()->json(['message' => "$room->name Berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function show($id)
    {
        $category = CategoryBarang::find($id);
        $TBarang = TemplateBarang::where('category_id', $category->id)->get();
        $templateBarang = TemplateBarang::orderBy('created_at', 'desc')->get();
        $categoryBarang = CategoryBarang::orderBy('created_at', 'desc')->get();

        return view('app-type.logistik.category-barang.show', [
            'categories' => CategoryBarang::all(),
            'category' => $category,
            'items' => $TBarang,
            'jumlah' => count(Barang::where('category_barang_id', $category->id)->get()),
            'templateBarang' => $templateBarang,
            'categoryBarang' => $categoryBarang,
        ]);
    }
}
