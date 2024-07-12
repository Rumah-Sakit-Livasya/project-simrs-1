<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{

    public function getDataMenu($id)
    {
        try {
            $menu = Menu::find($id);

            return response()->json([
                'menu' => $menu
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan menu.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store()
    {
        try {
            // Custom messages for validation
            $messages = [
                'title.required' => 'Title harus diisi!',
                'url.required' => 'URL harus diisi!',
                'permission.required' => 'Permission harus diisi!',
            ];

            // Validate the request with custom messages
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'url' => 'required',
                'permission' => 'required',
            ], $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = $errors->first(); // Get the first error message
                throw new \Exception($errorMessage);
            }

            $menu = Menu::create([
                'title' => request()->title,
                'url' => request()->url,
                'parent_id' => request()->parent_id,
                'icon' => request()->icon,
                'sort_order' => request()->sort_order,
                'permission' => request()->permission,
            ]);
            // Create permission if it doesn't exist
            $permission = Permission::firstOrCreate(['name' => request()->permission]);

            // Return response
            return response()->json(['message' => 'Menu Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update($id)
    {
        try {
            // Custom messages for validation
            $messages = [
                'title.required' => 'Title harus diisi!',
                'url.required' => 'URL harus diisi!',
                'permission.required' => 'Permission harus diisi!',
            ];

            // Validate the request with custom messages
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'url' => 'required',
                'permission' => 'required',
            ], $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = $errors->first(); // Get the first error message
                throw new \Exception($errorMessage);
            }

            $menu = Menu::findOrFail($id);
            $permission = Permission::findByName($menu->permission);
            $menu->update([
                'title' => request()->title,
                'url' => request()->url,
                'parent_id' => request()->parent_id,
                'icon' => request()->icon,
                'sort_order' => request()->sort_order,
                'permission' => request()->permission,
            ]);

            $permission->update(['name' => request()->permission]);
            // Create permission if it doesn't exist

            // Return response
            return response()->json(['message' => 'Menu Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {

            $menu = Menu::findOrFail($id);
            $permission = Permission::findByName($menu->permission);
            $menu->delete();

            $permission->delete();
            // Create permission if it doesn't exist

            // Return response
            return response()->json(['message' => 'Menu Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
