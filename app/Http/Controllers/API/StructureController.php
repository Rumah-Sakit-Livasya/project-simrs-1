<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function getStructure($id)
    {
        try {
            $structure = Structure::findOrFail($id);
            return response()->json($structure, 200);
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
                'child_organization' => 'required',
                'parent_organization' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            Structure::create(request()->all());
            //return response
            return response()->json(['message' => 'Structure Berhasil di Tambahkan!']);
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
                'child_organization' => 'required',
                'parent_organization' => 'nullable',
            ]);

            //find company by ID
            $structure = Structure::find($id);
            $structure->update($validator);
            //return response
            return response()->json(['message' => 'Structure Berhasil di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $structure = Structure::find($id);
            $structure->delete();
            //return response
            return response()->json(['message' => 'Structure Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }
}
