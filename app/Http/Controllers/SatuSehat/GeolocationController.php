<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use App\Models\GeoLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeolocationController extends Controller
{
    public function index()
    {
        $location = GeoLocation::first();

        return view('pages.simrs.satu-sehat.geolocation', [
            'location' => $location
        ]);
    }

    public function mapLocation(Request $request)
    {
        try {
            // Logika bisnis tetap sama
            $success = true;

            if ($success) {
                return response()->json([
                    'msg' => 'success',
                    'text' => 'Lokasi RS Berhasil Terkoneksi Satu Sehat!'
                ]);
            } else {
                return response()->json([
                    'msg' => 'gagal',
                    'text' => 'Proses mapping gagal, silahkan coba lagi.'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Gagal Mapping Lokasi Satu Sehat: ' . $e->getMessage());
            return response()->json([
                'msg' => 'error',
                'text' => 'Terjadi kesalahan internal pada server.'
            ], 500);
        }
    }
}
