<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCode;
use Illuminate\Http\Request;

class AttendanceCodeController extends Controller
{
    public function getAttendanceCode($id)
    {
        try {
            $attendance_code = AttendanceCode::findOrFail($id);
            return response()->json($attendance_code, 200);
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
                'code' => 'required',
                'description' => 'required',
                'total_hari' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            AttendanceCode::create(request()->all());
            //return response
            return response()->json(['message' => 'Kode Absensi Berhasil di Tambahkan!']);
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
                'code' => 'required',
                'description' => 'required',
                'total_hari' => 'nullable',
            ]);

            //find company by ID
            $attendance_code = AttendanceCode::where('id', $id)->first();
            $attendance_code->update($validator);
            //return response
            return response()->json(['message' => 'Kode Absensi Berhasil di Update!']);
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
            $attendance_code = AttendanceCode::find($id);
            $attendance_code->delete();
            //return response
            return response()->json(['message' => 'Kode Absensi Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }
}
