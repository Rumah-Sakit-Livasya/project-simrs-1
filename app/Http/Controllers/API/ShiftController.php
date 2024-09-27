<?php

namespace App\Http\Controllers\API;

use App\Exports\ShiftExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShiftController extends Controller
{

    public function getShift($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            return response()->json($shift, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function store()
    {
        try {
            $validator = request()->validate([
                'name' => 'required',
                'time_in' => 'required',
                'time_out' => 'required',
            ]);

            $validator['status'] = 'aktif';

            Shift::create($validator);
            //return response
            return response()->json(['message' => 'Shift Berhasil di Tambahkan!']);
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
                'time_in' => 'required',
                'time_out' => 'required',
                'status' => 'required',
            ]);

            //find company by ID
            $shift = Shift::find($id);
            $shift->update($validator);
            //return response
            return response()->json(['message' => 'Shift Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'No result',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $shift = Shift::find($id);
            $shift->delete();
            //return response
            return response()->json(['message' => 'Shift Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result',
                'errorLaravel' => $e->getMessage()
            ], 404);
        }
    }

    public function export($organizationId, Request $request)
    {
        // return dd($user);
        $user = User::where('id', $request->user_id)->first();

        if ($user->hasRole('super admin')) {
            if ($request->organization_id === null) {
                // Ambil data karyawan berdasarkan organization_id
                $employees = Employee::where('is_active', 1)->select('id', 'email', 'fullname') // Memilih hanya kolom yang dibutuhkan
                    ->get();
            } else {
                $employees = Employee::where('organization_id', $request->organization_id)
                    ->where('is_active', 1)
                    ->select('id', 'email', 'fullname') // Memilih hanya kolom yang dibutuhkan
                    ->get();
            }
        } else {
            $employees = Employee::where('organization_id', $organizationId)
                ->where('is_active', 1)
                ->select('id', 'email', 'fullname') // Memilih hanya kolom yang dibutuhkan
                ->get();
        }

        $year = $request->year;
        $month = $request->month;

        foreach ($employees as $employee) {
            $attendances = $employee->attendance;
            foreach ($attendances as $attendance) {
                $shift = $attendance->shift;
                $shifts[] = $shift;
            }
        }

        // Nama file Excel
        $fileName = 'shift.xlsx';
        ini_set('memory_limit', '512M');
        // Export data karyawan dan data nama shift ke dalam file Excel
        return Excel::download(new ShiftExport($employees, $shifts, $month, $year), $fileName);
    }
}
