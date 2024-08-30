<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function getPermission($id)
    {
        try {
            $permission = Permission::findById($id);

            return response()->json([
                'permission' => $permission
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan permission.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findById($id);

            // $validator = Validator::make($request->all(), [
            //     'name' => 'required|string|max:255|unique:permissions,name',
            // ]);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
                'group' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $permission->update($validator->validated());

            return response()->json([
                'message' => 'Permission berhasil diubah.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan permission.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name',
                'group' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Simpan data ke database
            Permission::create($validator->validated());

            // Kembalikan respon sukses
            return response()->json([
                'message' => 'Permission berhasil ditambahkan.'
            ], 200);
        } catch (Exception $e) {
            // Tangani kesalahan lain
            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan permission.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findById($id);

            $permission->delete();

            return response()->json([
                'message' => 'Permission berhasil dihapus.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan permission.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
