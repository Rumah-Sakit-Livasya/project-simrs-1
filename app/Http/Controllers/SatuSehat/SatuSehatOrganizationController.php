<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement; // <-- GANTI MODEL
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SatuSehatOrganizationController extends Controller
{
    public function index($category = 'poliklinik')
    {
        $validCategories = ['poliklinik', 'penunjang_medis', 'lainnya'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        // Gunakan model Departement untuk query
        $departments = Departement::where('category', $category)->get();

        return view('pages.simrs.satu-sehat.departments', [
            'departments' => $departments, // Kirim dengan nama variabel yang jelas
            'activeCategory' => $category
        ]);
    }

    // Gunakan Route-Model Binding dengan Departement
    public function map(Request $request, Departement $departement)
    {
        try {
            // Simulasi mendapatkan ID dari API Satu Sehat
            $newOrganizationId = (string) Str::uuid();

            // Update kolom baru di model Departement
            $departement->update(['satu_sehat_organization_id' => $newOrganizationId]);

            return response()->json([
                'msg' => 'success',
                'text' => 'Department berhasil di-mapping!',
                'organization_id' => $newOrganizationId
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal Mapping Department Satu Sehat: ' . $e->getMessage());
            return response()->json(['msg' => 'error', 'text' => 'Terjadi kesalahan internal server.'], 500);
        }
    }
}
