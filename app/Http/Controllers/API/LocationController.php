<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function getLocation($id)
    {
        try {
            $location = Location::findOrFail($id);
            return response()->json($location, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception("Data harus diisi semua!");
            }

            Location::create([
                'name' => request()->name,
                'latitude' => request()->latitude,
                'longitude' => request()->longitude,
            ]);

            //return response
            return response()->json(['message' => 'Lokasi Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
    public function update($id)
    {
        try {
            //define validation rules
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception("Data harus diisi semua!");
            }

            //find company by ID
            $location = Location::find($id);
            $location->update(request()->all());
            //return response
            return response()->json(['message' => 'Lokasi Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $location = Location::find($id);
            $location->delete();
            //return response
            return response()->json(['message' => 'Lokasi Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
