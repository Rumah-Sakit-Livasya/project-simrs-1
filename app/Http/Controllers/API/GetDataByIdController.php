<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class GetDataByIdController extends Controller
{
    public function getOrganization($id)
    {
        try {
            $organization = Organization::findOrFail($id);
            return response()->json($organization, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
