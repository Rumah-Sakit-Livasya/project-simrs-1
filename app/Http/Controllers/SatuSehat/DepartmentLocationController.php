<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepartmentLocationController extends Controller
{
    public function index($category = 'rawat_jalan')
    {
        $validCategories = ['rawat_jalan', 'rawat_inap', 'penunjang_medis'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        // --- INI BAGIAN SOLUSINYA ---
        // Buat variabel untuk menampung kategori yang akan dicari di database.
        $dbCategory = $category;

        // Jika kategori dari URL adalah 'rawat_jalan',
        // ubah variabel pencarian menjadi 'poliklinik'.
        if ($category === 'rawat_jalan') {
            $dbCategory = 'poliklinik';
        }
        // Untuk kategori lain ('rawat_inap', 'penunjang_medis'), namanya sudah cocok
        // jadi tidak perlu diubah.
        // -----------------------------

        // Gunakan variabel $dbCategory untuk query ke database.
        $departments = Departement::where('category', $dbCategory)->get();

        return view('pages.simrs.satu-sehat.department-locations', [ // Pastikan path view Anda benar
            'departments' => $departments,
            'activeCategory' => $category // Kirim kategori ASLI dari URL ke view untuk menandai tab mana yang aktif.
        ]);
    }


    public function map(Request $request, Departement $departement)
    {
        // Validasi Kunci: Pastikan departemen sudah di-mapping sebagai Organization
        if (is_null($departement->satu_sehat_organization_id)) {
            return response()->json([
                'msg' => 'gagal',
                'text' => 'Department ini belum di-mapping sebagai Organization. Silahkan lakukan mapping di halaman Department terlebih dahulu!'
            ], 400); // 400 Bad Request
        }

        try {
            $newLocationId = (string) Str::uuid();
            $departement->update([
                'satu_sehat_location_id' => $newLocationId,
                'location_status' => 'active',
                'location_physical_type' => 'ro', // ro = Room
                'location_mode' => 'instance'
            ]);

            return response()->json([
                'msg' => 'success',
                'text' => 'Department berhasil di-mapping sebagai Lokasi!',
                'location_id' => $newLocationId
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal Mapping Department ke Location: ' . $e->getMessage());
            return response()->json(['msg' => 'error', 'text' => 'Terjadi kesalahan internal server.'], 500);
        }
    }
}
