<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobPositionController extends Controller
{
    public function getJobPosition($id)
    {
        try {
            $JobPosition = JobPosition::findOrFail($id);
            return response()->json($JobPosition, 200);
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

            JobPosition::create([
                'name' => request()->name,
            ]);
            //return response
            return response()->json(['message' => 'Job Position Berhasil di Tambahkan!']);
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
            $JobPosition = JobPosition::find($id);
            $JobPosition->update($validator);
            //return response
            return response()->json(['message' => 'Job Position Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $JobPosition = JobPosition::find($id);
            $JobPosition->delete();
            //return response
            return response()->json(['message' => 'Job Position Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
