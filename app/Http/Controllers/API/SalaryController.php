<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    public function getSalary($id)
    {
        try {
            $employee = Employee::with('salary')->where('id', $id)->first();
            return response()->json($employee, 200);
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
            $validator = request()->validate([
                'basic_salary' => 'required|integer',
                'tunjangan_jabatan' => 'required|integer',
                'tunjangan_profesi' => 'required|integer',
                'tunjangan_makan_dan_transport' => 'required|integer',
                'tunjangan_masa_kerja' => 'required|integer',
                'guarantee_fee' => 'required|integer',
                'uang_duduk' => 'required|integer',
                'tax_allowance' => 'required|integer',
            ]);

            //find company by ID
            $employee = Employee::where('id', $id)->first();

            $salary = $employee->salary->where('employee_id', $employee->id)->first();
            $salary->update($validator);
            //return response
            return response()->json(['message' => 'Salary successfully updated!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
