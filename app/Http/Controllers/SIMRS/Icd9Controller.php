<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Icd9Procedure; // Pastikan path model benar
use Illuminate\Http\Request;

class Icd9Controller extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');
        if (empty($searchTerm)) {
            return response()->json([]);
        }

        $results = Icd9Procedure::where('code', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->limit(20) // Batasi hasil untuk performa
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
