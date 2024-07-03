<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
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

    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            Organization::create([
                'name' => request()->name,
            ]);
            //return response
            return response()->json(['message' => 'Organisasi Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function update($id)
    {
        try {
            //define validation rules
            $validator = request()->validate([
                'name' => 'required',
            ]);

            //find company by ID
            $organization = Organization::find($id);
            $organization->update($validator);
            //return response
            return response()->json(['message' => 'Detail Perusahaan Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $organization = Organization::find($id);
            $organization->delete();
            //return response
            return response()->json(['message' => 'Organisasi Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
