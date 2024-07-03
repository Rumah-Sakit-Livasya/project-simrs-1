<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function getBank($id)
    {
        try {
            $bank = Bank::findOrFail($id);
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
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            Bank::create([
                'name' => request()->name,
            ]);
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
                'name' => 'required',
            ]);

            //find company by ID
            $bank = Bank::find($id);
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
            $bank = Bank::find($id);
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
