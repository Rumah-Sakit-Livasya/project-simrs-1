<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateBarangController extends Controller
{
    public function index()
    {
        $templateBarang = TemplateBarang::orderBy('created_at', 'desc')->get();
        $categoryBarang = CategoryBarang::orderBy('created_at', 'desc')->get();
        return view('app-type.logistik.template-barang.index', compact('templateBarang', 'categoryBarang'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => "required|max:255",
            'name' => "required|max:255",
            'barang_code' => "required|max:255",
            'foto' => "max:5120",
        ]);

        try {
            $store = TemplateBarang::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTemplate($id)
    {
        try {
            $template = TemplateBarang::findOrFail($id);

            return response()->json([
                'category_id' => $template->category_id,
                'name' => $template->name,
                'barang_code' => $template->barang_code,
                'foto' => $template->foto,
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
            'category_id' => "required|max:255",
            'name' => "required|max:255",
            'barang_code' => "required|max:255",
            'foto' => "file|max:5120",
        ]);

        try {
            $template = TemplateBarang::findOrFail($id);
            $template->update($validatedData);
            return response()->json(['message' => "$template->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $template = TemplateBarang::find($id);
            $template->delete();
            return response()->json(['message' => "$template->name Berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $templateBarang = TemplateBarang::findOrFail($id);
        $barang = Barang::where("template_barang_id", $templateBarang->id)->orderBy('urutan_barang')->get();
        $allRoom = RoomMaintenance::orderBy('name', 'asc')->get();

        // Check if the user has admin permissions
        if (Auth::user()->can('admin inventaris barang')) {
            // If the user is an admin, retrieve all items
            $companies = Company::all();
        } else {
            // Get the organization of the authenticated user
            $companies = Auth::user()->employee->company;
        }

        return view('app-type.logistik.template-barang.show', [
            'barang' => $barang,
            'companies' => $companies,
            'templates' => TemplateBarang::orderBy('name')->get(),
            'nama_template' => $templateBarang,
            'categories' => CategoryBarang::orderBy('name')->get(),
            'allRoom' => $allRoom,
            'jumlah' => count(Barang::where('template_barang_id', $templateBarang->id)->get())
        ]);
    }
}
