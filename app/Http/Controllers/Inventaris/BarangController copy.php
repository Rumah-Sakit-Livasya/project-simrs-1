<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\ReportBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use App\Models\User;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $customName = $request->input('custom_name');
        $templateBarang = $request->input('template_barang_id');
        $kategoriBarang = $request->input('barang_category_id');
        $identitasBarang = $request->input('identitas_barang');
        $barang = [];

        // Cek apakah ada parameter pencarian
        if ($customName || $templateBarang || $kategoriBarang || $identitasBarang) {

            // Query pencarian
            $query = Barang::query();

            // Lakukan filter pencarian jika ada nilai dari input pencarian
            if ($customName) {
                $query->where('custom_name', 'like', '%' . $customName . '%');
            }
            if ($identitasBarang) {
                $query->where('item_code', 'like', '%' . $identitasBarang . '%');
            }
            if ($templateBarang) {
                $query->where('template_barang_id', $templateBarang);
            }
            if ($kategoriBarang) {
                $query->where('barang_category_id', $kategoriBarang);
            }

            // Ambil hasil pencarian
            $barang = $query->orderBy('custom_name', 'asc')->get();
        }
        $barang = Barang::orderBy('id', 'desc')
            // ->orWhere('room_id', 128)
            // ->orWhere('room_id', 129)
            // ->orWhere('room_id', 130)
            // ->orWhere('room_id', 132)
            // ->orWhere('room_id', 134)
            // ->orWhere('room_id', 135)
            // ->orWhere('room_id', 136)
            // ->skip(100)
            ->limit(100)
            ->get();

        return view('pages.inventaris.barang.index', [
            'barang' => $barang,
            'companies' => Company::all(),
            'templates' => TemplateBarang::orderBy('name', 'asc')->get(),
            'categories' => CategoryBarang::orderBy('name', 'asc')->get(),
            'rooms' => RoomMaintenance::orderBy('name', 'asc')->get(),
            'jumlah' => count(Barang::all())
        ]);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $validatedData = $request->validate([
            'custom_name' => "max:255",
            'template_barang_id' => "required|max:255",
            'condition' => "required|max:5120",
            'bidding_year' => "required|max:255",
            'merk' => "max:5120",
            'room_id' => "max:255",
        ]);

        $template = $validatedData['template_barang_id'];
        $category = TemplateBarang::where('id', $template)->get('category_id')->first()->category_id;
        $validatedData['barang_category_id'] = $category;

        $barang_code = RoomMaintenance::where('id', $request['room_id'])->first()->room_code;

        $barang_id = $request->template_barang_id;
        $template = TemplateBarang::where('id', $barang_id)->first();

        // Report Hasil
        $templateBarang = TemplateBarang::where('id', $request->template_barang_id)->first();

        if ($request->custom_name === null) {
            $nameBarang = $templateBarang->name;
        } else {
            $nameBarang = $request->custom_name;
        }

        if ($request->room_id === null) {
            $room = RoomMaintenance::findOrFail($request->room_id);
            $namaRuang = $room->name;
        } else {
            $namaRuang = "Sistem";
        }

        $reportData = [
            'barang_id' => $request->barang_id,
            'room_id' => $request->room_id
        ];
        $reportData['user_id'] = $user->id;
        $reportData['keterangan'] = strtoupper("$nameBarang telah ditambahkan ke $namaRuang oleh $user->name");

        $lastUrutanBarang = Barang::where('template_barang_id', $template->id)
            ->where('urutan_barang', '<>', '0')
            ->orderByDesc('urutan_barang')
            ->first();
        // Start from 0 if $lastUrutanBarang is null, otherwise from the last number
        $startUrutan = $lastUrutanBarang ? intval($lastUrutanBarang->urutan_barang) : 0;

        $companyCode = Company::findOrFail($request->company_id)->code;
        if ($request->jumlah) {

            for ($i = 1; $i <= $request->jumlah; $i++) {
                $urutanBarang = $startUrutan + $i;

                // Format the urutan_barang to have leading zeros
                $validatedData['urutan_barang'] = str_pad($urutanBarang, 3, '0', STR_PAD_LEFT);

                // Generate the item code
                $item_code = $companyCode . "/" . $template->category->category_code . "/" . $barang_code . "/" . $template->barang_code . "." . $template->name . " " . $validatedData['urutan_barang'] . "/" . $request->bidding_year;
                $validatedData['item_code'] = $item_code;

                // Create the Barang and ReportBarang records
                Barang::create($validatedData);
                ReportBarang::create($reportData);
            }
        }

        try {
            return response()->json(['message' => "Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function move(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'room_id' => "required|max:255",
        ]);

        return dd($barang);

        try {
            return response()->json(['message' => "Berhasil pindahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            return response()->json([
                'id' => $barang->id,
                'custom_name' => $barang->custom_name,
                'item_code' => $barang->item_code,
                'condition' => $barang->condition,
                'template_barang_id' => $barang->template_barang_id,
                'merk' => $barang->merk,
                'bidding_year' => $barang->bidding_year,
                'urutan_barang' => $barang->urutan_barang,
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
            'custom_name' => "max:255",
            'item_code' => "required|max:255",
            'condition' => "required|max:255",
            'template_barang_id' => "required|max:255",
            'merk' => "max:255",
            'bidding_year' => "required|max:255",
            'urutan_barang' => "required|max:255",
        ]);

        try {
            $barang = Barang::findOrFail($id);
            $barang->update($validatedData);
            return response()->json(['message' => "$barang->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $barang = Barang::find($id);
            $barang->delete();
            return response()->json(['message' => "$barang->name Berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
