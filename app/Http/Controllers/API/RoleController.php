<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function getRole($id)
    {
        try {
            $role = Role::findById($id);
            $permissions = $role->getPermissionNames();

            return response()->json([
                'role' => $role,
                'permissions' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'guard_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $role = Role::create([
                'name' => request()->name,
                'guard_name' => request()->guard_name,
            ]);

            //return response
            return response()->json(['message' => 'Role Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {
            //define validation rules
            $validator = request()->validate([
                'name' => 'required',
                'guard_name' => 'required',
            ]);

            //find company by ID
            $role = Role::find($id);
            $role->update($validator);
            //return response
            return response()->json(['message' => 'Role Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // dd($id);
        try {
            $role = Role::findById($id);
            if (!$role) {
                return response()->json(['error' => 'Role tidak ditemukan'], 404);
            }
            $role->delete();
            return response()->json(['message' => 'Role Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
