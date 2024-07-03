<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeductionController extends Controller
{
    public function getDeduction($id)
    {
        try {
            $employee = Employee::with('deduction')->where('id', $id)->first();
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
                'potongan_keterlambatan' => 'required|integer',
                'potongan_izin' => 'required|integer',
                'potongan_sakit' => 'required|integer',
                'simpanan_pokok' => 'required|integer',
                'potongan_koperasi' => 'required|integer',
                'potongan_absensi' => 'required|integer',
                'potongan_bpjs_kesehatan' => 'required|integer',
                'potongan_bpjs_ketenagakerjaan' => 'required|integer',
                'potongan_pajak' => 'required|integer',
            ]);

            //find company by ID
            $employee = Employee::where('id', $id)->first();

            $deduction = $employee->deduction->where('employee_id', $employee->id)->first();
            if ($deduction !== null) {
                $deduction->update($validator);
            } else {
                $validator['employee_id'] = $employee->id;
                $deduction = Deduction::create($validator);
            }

            //return response
            return response()->json(['message' => 'Deduction successfully updated!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
