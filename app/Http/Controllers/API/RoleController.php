<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function getRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            // Get permission IDs associated with this role
            $permissionIds = $role->permissions->pluck('id');

            return response()->json([
                'role' => $role,
                'permissionIds' => $permissionIds
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            Role::create($validator->validated());
            return response()->json(['message' => 'Role Berhasil di Tambahkan!'], 201);
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menambahkan role.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $role->update($validator->validated());
            return response()->json(['message' => 'Role Berhasil di Update!']);
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memperbarui role.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['message' => 'Role Berhasil di Hapus!']);
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus role.'], 500);
        }
    }

    /**
     * Menampilkan halaman untuk assign permissions ke role.
     * Ini adalah metode yang kita perbaiki.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function assignPermissions(Role $role)
    {
        // 1. Ambil semua permissions dan kelompokkan berdasarkan kolom 'group'.
        // Ini adalah logika yang benar dari contoh Anda.
        $permissionsByGroup = Permission::orderBy('group')->get()->groupBy('group');

        // 2. Dapatkan ID dari permissions yang sudah dimiliki oleh role ini.
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // 3. Kirim data yang sudah benar ke view.
        return view('pages.master-data.role.assign-permissions', [
            'role' => $role,
            'permissionsByGroup' => $permissionsByGroup,
            'rolePermissions' => $rolePermissions,
            // 'getNotify' => $this->getNotify()
        ]);
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            // BENAR: Ambil instance model Permission berdasarkan ID dari request
            $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();

            // Sekarang berikan koleksi model Permission ke syncPermissions
            $role->syncPermissions($permissions);

            return response()->json(['message' => 'Permissions for role ' . $role->name . ' updated successfully!']);
        } catch (\Exception $e) {
            // Menggunakan Log lebih baik untuk debugging
            \Log::error('Permission sync error for role ' . $role->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while updating permissions.'], 500);
        }
    }
}
