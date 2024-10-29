<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\MaintenanceBarang;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceBarangController extends Controller
{
    public function index($id)
    {
        $barang = Barang::where('id', $id)->first();

        return view('pages.inventaris.maintenance.index', [
            'maintenances' => MaintenanceBarang::where('barang_id', $id)->orderBy('created_at', 'asc')->get(),
            'barang' => $barang,
        ]);
    }

    public function getMaintenance($id)
    {
        try {
            $maintenance = MaintenanceBarang::findOrFail($id);

            return response()->json([
                'barang_id' => $maintenance->barang_id,
                'user_id' => $maintenance->user_id,
                'kondisi' => $maintenance->kondisi,
                'hasil' => $maintenance->hasil,
                'rtl' => $maintenance->rtl,
                'foto' => $maintenance->foto,
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

    public function getFoto($id)
    {
        $maintenance = MaintenanceBarang::findOrFail($id);

        return response()->json([
            'foto' => $maintenance->foto // or whatever attribute holds the image URL
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barang_id' => 'required',
            'user_id' => 'required',
            'kondisi' => 'required',
            'hasil' => 'required',
            'tanggal' => 'required|date',
            'rtl' => 'required',
            'status' => 'required',
            'estimasi' => 'nullable',
            'keterangan' => 'nullable',
            'foto' => "max:5120",
        ], [
            'tanggal.required' => 'Kolom tanggal harus diisi.',
            'tanggal.date' => 'Kolom tanggal harus berupa format tanggal yang valid.',
            'kondisi.required' => 'Kolom kondisi harus diisi.',
            'hasil.required' => 'Kolom hasil harus diisi.',
            'rtl.required' => 'Kolom rencana tindak lanjut harus diisi.',
            'status.required' => 'Kolom Status lanjut harus diisi.',
            'foto.max' => 'Ukuran file foto tidak boleh melebihi 5120 kilobita (5 MB).',
        ]);

        $validatedData['user_id'] = $request->user_id;
        $validatedData['foto'] = $request->file('foto')->store('maintenance', 'public');

        try {
            $store = MaintenanceBarang::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
