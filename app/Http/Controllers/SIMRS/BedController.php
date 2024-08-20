<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bed;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index($id)
    {
        $room = Room::findOrFail($id);
        $beds = $room->beds;
        return view('pages.simrs.master-data.setup.beds.index', compact('room', 'beds'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required',
            'nama_tt' => 'required',
            'no_tt' => 'required',
            'is_tambahan' => 'required',
        ]);

        try {
            $store = Bed::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBed($id)
    {
        try {
            $bed = Bed::findOrFail($id);

            return response()->json([
                'nama_tt' => $bed->nama_tt,
                'no_tt' => $bed->no_tt,
                'is_tambahan' => $bed->is_tambahan,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_tt' => 'required',
            'no_tt' => 'required',
            'is_tambahan' => 'nullable',
        ]);

        try {
            $bed = Bed::findOrFail($id);
            $bed->update($validatedData);
            return response()->json(['message' => ' berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $bed = Bed::find($id);
            $bed->delete();
            return response()->json(['message' => ' berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
