<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JobLevel;
use Illuminate\Http\Request;

class JobLevelController extends Controller
{
    public function getJobLevel($id)
    {
        try {
            $jobLevel = JobLevel::findOrFail($id);
            return response()->json($jobLevel, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function store()
    {
        try {
            $validator = \Validator::make(request()->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            JobLevel::create([
                'name' => request()->name,
            ]);
            //return response
            return response()->json(['message' => 'Job Level Berhasil di Tambahkan!']);
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
            $jobLevel = JobLevel::find($id);
            $jobLevel->update($validator);
            //return response
            return response()->json(['message' => 'Job Level Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $jobLevel = JobLevel::find($id);
            $jobLevel->delete();
            //return response
            return response()->json(['message' => 'Job Level Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
