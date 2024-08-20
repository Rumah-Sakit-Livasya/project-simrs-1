<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index($id)
    {
        $kelas_rawat = KelasRawat::findOrFail($id);
        $rooms = $kelas_rawat->rooms;
        return view('pages.simrs.master-data.setup.rooms.index', compact('kelas_rawat', 'rooms'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kelas_rawat_id' => 'required',
            'ruangan' => 'required',
            'no_ruang' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            $store = Room::create($validatedData);
            return response()->json(['message' => ' Berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRoom($id)
    {
        try {
            $room = Room::findOrFail($id);

            return response()->json([
                'ruangan' => $room->ruangan,
                'no_ruang' => $room->no_ruang,
                'keterangan' => $room->keterangan,
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
            'ruangan' => 'required',
            'no_ruang' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            $room = Room::findOrFail($id);
            $room->update($validatedData);
            return response()->json(['message' => ' Berhasil diupdate!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $room = Room::find($id);
            $room->delete();
            return response()->json(['message' => ' Berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
