<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BankEmployee;
use Illuminate\Http\Request;

class BankEmployeeController extends Controller
{
    public function getBankEmployee($id)
    {
        try {
            $bank = BankEmployee::findOrFail($id);
            return response()->json($bank, 200);
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
                'employee_id' => 'required',
                'bank_id' => 'required',
                'account_number' => 'required',
                'account_holder_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            BankEmployee::create(request()->all());
            //return response
            return response()->json(['message' => 'Bank Berhasil di Tambahkan!']);
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
                'employee_id' => 'required',
                'bank_id' => 'required',
                'account_number' => 'required',
                'account_holder_name' => 'required',
            ]);

            //find company by ID
            $bank = BankEmployee::find($id);
            $bank->update($validator);
            //return response
            return response()->json(['message' => 'Bank Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $bank = BankEmployee::find($id);
            $bank->delete();
            //return response
            return response()->json(['message' => 'Bank Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
