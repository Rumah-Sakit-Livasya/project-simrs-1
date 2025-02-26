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

    public function getKelurahan(Request $request)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $request->kecamatan_id)
            ->pluck('name', 'id');
        return response()->json($kelurahan);
    }
}
