<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Kabupaten;
use App\Models\SIMRS\Kecamatan;
use App\Models\SIMRS\Kelurahan;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getKabupaten(Request $request)
    {
        $kabupaten = Kabupaten::where('provinsi_id', $request->provinsi_id)
            ->pluck('name', 'id');
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = Kecamatan::where('kabupaten_id', $request->kabupaten_id)
            ->pluck('name', 'id');
        return response()->json($kecamatan);
    }

    // public function getKelurahan(Request $request)
    // {
    //     $kelurahan = Kelurahan::where('kecamatan_id', $request->kecamatan_id)
    //         ->pluck('name', 'id');
    //     return response()->json($kelurahan);
    // }

    public function getKecamatanByKelurahan(Request $request)
    {
        $kelurahanId = $request->kelurahan_id;

        // Query untuk mendapatkan data kecamatan, kabupaten, dan provinsi berdasarkan kelurahan
        $kelurahan = Kelurahan::with('kecamatan.kabupaten.provinsi')->find($kelurahanId);

        if ($kelurahan) {
            return response()->json([
                'kecamatan' => $kelurahan->kecamatan,
                'kabupaten' => $kelurahan->kecamatan->kabupaten,
                'provinsi' => $kelurahan->kecamatan->kabupaten->provinsi,
            ]);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function getKelurahan(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mendapatkan data kelurahan beserta relasi kecamatan berdasarkan pencarian
        $kelurahans = Kelurahan::with('kecamatan')
            ->where('name', 'like', "%{$search}%")
            ->limit(10) // Batasi jumlah hasil
            ->get();

        return response()->json($kelurahans);
    }
}
