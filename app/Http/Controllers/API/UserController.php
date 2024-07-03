<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
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
                'employee_id' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([
                'name' => request()->name,
                'employee_id' => request()->employee_id,
                'email' => request()->email,
            ]);

            $user->assignRole('employee');

            //return response
            return response()->json(['message' => 'User Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update($id)
    {
        try {

            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::find($id);
            $employee = Employee::find($user->employee_id);

            //find company by ID
            $user->update([
                'email' => request()->email,
                'name' => request()->name,
            ]);
            $employee->update([
                'email' => request()->email,
                'fullname' => request()->name,
            ]);
            //return response
            return response()->json(['message' => 'User Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function updateRole($id)
    {
        try {
            $role = Role::find(request()->role);

            $user = User::find($id);

            // Hapus semua peran yang telah ditetapkan sebelumnya untuk pengguna ini
            $user->roles()->detach();

            // Tetapkan peran baru untuk pengguna
            $user->assignRole($role->name);

            // Berhasil memperbarui peran pengguna
            return response()->json(['message' => 'Role pengguna berhasil diperbarui!']);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            //return response
            return response()->json(['message' => 'User Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
