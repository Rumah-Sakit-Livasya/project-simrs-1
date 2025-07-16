<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
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
    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Simpan hari libur
            Holiday::create($request->all());

            // Ambil semua employee yang merupakan manajemen
            $employeeManagement = Employee::where('is_management', 1)->get();

            foreach ($employeeManagement as $employee) {
                $existingAttendance = Attendance::where('employee_id', $employee->id)
                    ->where('date', $request->date)
                    ->first();

                if (!$existingAttendance) {
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'date' => $request->date,
                        'is_day_off' => 1,
                        'attendance_code_id' => 37,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    // Jika sudah ada, update saja
                    $existingAttendance->update([
                        'is_day_off' => 1,
                        'shift_id' => 37,
                    ]);
                }
            }

            return response()->json(['message' => 'Day Off berhasil ditambahkan dan absensi manajemen disesuaikan.']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'errorLaravel' => $e->getMessage()
            ], 500);
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
