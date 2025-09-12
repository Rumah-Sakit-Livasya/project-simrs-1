<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Icd10Diagnostic; // Pastikan path model benar
use Illuminate\Http\Request;

class Icd10Controller extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');
        if (empty($searchTerm)) {
            return response()->json([]);
        }

        // --- LOGIKA PENCARIAN AKURAT ---
        $results = Icd10Diagnostic::query()
            // Prioritaskan hasil yang kodenya dimulai dengan istilah pencarian (sangat akurat)
            ->where('code', 'LIKE', "{$searchTerm}%")
            // Kemudian cari juga di deskripsi
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->limit(20)
            ->get();

        $formattedResults = $results->map(function ($item) {
            return [
                'id'   => $item->code,
                'text' => $item->code . ' - ' . $item->description,
            ];
        });

        return response()->json($formattedResults);
    }
}
