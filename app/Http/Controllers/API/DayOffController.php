<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class DayOffController extends Controller
{
    public function getHoliday($id)
    {
        try {
            $jobLevel = Holiday::findOrFail($id);
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

            Holiday::create(request()->all());
            //return response
            return response()->json(['message' => 'Day Off Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }

    public function update($id)
    {
        try {
            //define validation rules
            $validator = request()->validate([
                'name' => 'required',
                'date' => 'required',
            ]);

            //find company by ID
            $jobLevel = Holiday::find($id);
            $jobLevel->update($validator);
            //return response
            return response()->json(['message' => 'Day Off Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'No result',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $jobLevel = Holiday::find($id);
            $jobLevel->delete();
            //return response
            return response()->json(['message' => 'Day Off Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }
}
