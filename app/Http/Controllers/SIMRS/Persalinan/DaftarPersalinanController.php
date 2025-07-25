<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Persalinan\DaftarPersalinan;
use Illuminate\Http\Request;

class DaftarPersalinanController extends Controller
{
    public function index()
    {
        $daftar_persalinan = DaftarPersalinan::all();
        return view('pages.simrs.master-data.persalinan.daftar-persalinan.index', compact('daftar_persalinan'));
    }

    public function getPersalinan($id)
    {
        try {
            $daftar_persalinan = DaftarPersalinan::findOrFail($id);

            return response()->json([
                'tipe' => $daftar_persalinan->tipe,
                'kode' => $daftar_persalinan->kode,
                'nama_persalinan' => $daftar_persalinan->nama_persalinan,
                'nama_billing' => $daftar_persalinan->nama_billing,
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipe' => 'required',
            'kode' => 'required',
            'nama_persalinan' => 'required',
            'nama_billing' => 'required',
        ]);

        try {
            $store = DaftarPersalinan::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function tarifPersalinan($id)
    {
        $persalinan = DaftarPersalinan::findOrFail($id);
        $grup_penjamin = GroupPenjamin::all();
        $kelas_rawat = KelasRawat::select('id', 'kelas')->get();

        return view('pages.simrs.master-data.persalinan.tarif.index', compact('persalinan', 'grup_penjamin', 'kelas_rawat'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tipe' => 'required',
            'kode' => 'required',
            'nama_persalinan' => 'required',
            'nama_billing' => 'required',
        ]);

        try {
            $grup_daftar_persalinan = DaftarPersalinan::find($id);
            $grup_daftar_persalinan->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $grup_daftar_persalinan = DaftarPersalinan::find($id);
            $grup_daftar_persalinan->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
