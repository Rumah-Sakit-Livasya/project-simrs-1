<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
// Hapus Hash karena hashing sudah ditangani oleh Model
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // ... (method getUser dan getByName tidak berubah)
    public function getUser($id)
    {
        try {
            // Eager load roles untuk efisiensi
            $user = User::with('roles')->findOrFail($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getByName(Request $request)
    {
        try {
            $employee = Employee::where('fullname', 'LIKE', '%' . $request->q . '%')->where('is_active', 1)->limit(5)->get();

            if ($employee->isEmpty()) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json(['data' => $employee], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function store()
    {
        try {
            // PERUBAHAN DI SINI: Tambahkan validasi untuk password
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'employee_id' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8', // Wajibkan password saat membuat user baru
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // PERUBAHAN DI SINI: Ambil password dari request
            $user = User::create([
                'name' => request()->name,
                'employee_id' => request()->employee_id,
                'email' => request()->email,
                'password' => request()->password, // Hashing otomatis oleh Model User
            ]);

            // Assign role default jika ada
            if (request()->has('roles')) {
                $roles = Role::whereIn('id', request()->roles)->pluck('name');
                $user->assignRole($roles);
            } else {
                $user->assignRole('employee'); // Role default
            }

            return response()->json(['message' => 'User Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id)
    {
        try {
            $user = User::findOrFail($id);

            // PERUBAHAN DI SINI: Tambahkan validasi untuk password (opsional saat update)
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8', // Password tidak wajib diisi saat update
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user->name = request()->name;
            $user->email = request()->email;

            // PERUBAHAN DI SINI: Cek jika ada input password baru
            if (request()->filled('password')) {
                $user->password = request()->password; // Hashing otomatis oleh Model User
            }

            $user->save(); // Simpan semua perubahan

            // Update data employee jika perlu
            $employee = Employee::find($user->employee_id);
            if ($employee) {
                $employee->update([
                    'email' => request()->email,
                    'fullname' => request()->name,
                ]);
            }

            return response()->json(['message' => 'User Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    // ... (method updateRole, destroy, storePermissions tidak berubah)
    public function updateRole($id)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id' // Validasi setiap item dalam array
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::findOrFail($id);

            // Ambil nama role dari ID yang diberikan
            $roles = Role::whereIn('id', request()->roles)->pluck('name');

            // Sync roles, ini akan menghapus semua role lama dan menggantinya dengan yang baru
            $user->syncRoles($roles);

            // Berhasil memperbarui peran pengguna
            return response()->json(['message' => 'Role pengguna berhasil diperbarui!']);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
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
