<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
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

    public function storePermissions(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
                'user_id' => 'required|exists:users,id'
            ]);

            // Get the authenticated user (you might be passing user ID, adjust accordingly)
            $user = User::findOrFail($request->user_id); // or User::find($request->user_id) if you pass user ID

            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                ], 404);
            }
            // Fetch permission names by their IDs
            $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

            // Sync the permissions
            $user->syncPermissions($permissionNames);

            return response()->json([
                'message' => 'Permissions assigned successfully!',
            ], 200);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error assigning permissions: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
