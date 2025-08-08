<?php

namespace App\Http\Controllers;

use App\Models\TestingData;
use Illuminate\Http\Request;

class TestingDataController extends Controller
{
    public function index()
    {
        $testingData = TestingData::all();
        return view('testing.index', compact('testingData'));
    }

    public function getData($id)
    {
        try {
            // Find the data by ID
            $testingData = TestingData::findOrFail($id);
            return response()->json($testingData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data not found'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $data = $request->validate([
                'nama' => 'required',
                'tanggal' => 'required',
            ]);

            // Store the data in the database
            TestingData::create($data);

            return response()->json(['message' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $data = $request->validate([
                'nama' => 'required',
                'tanggal' => 'required',
            ]);

            // Find the existing data
            $testingData = TestingData::find($id);
            if (!$testingData) {
                return response()->json(['error' => 'Data not found'], 404);
            }
            // Update the data
            $testingData->update($data);

            return response()->json(['message' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Delete the data
            $testingData = TestingData::find($id);
            if (!$testingData) {
                return response()->json(['error' => 'Data not found'], 404);
            }
            $testingData->delete();
            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
