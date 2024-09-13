<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use Illuminate\Http\Request;

class RoomMaintenanceController extends Controller
{
    public function index()
    {
        $rooms = RoomMaintenance::orderBy('created_at', 'desc')->get();
        return view('pages.inventaris.rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'room_code' => 'max:255|required',
            'floor' => 'integer|max:255|required',
        ]);

        try {
            $store = RoomMaintenance::create($validatedData);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRoom($id)
    {
        try {
            $room = RoomMaintenance::findOrFail($id);

            return response()->json([
                'name' => $room->name,
                'room_code' => $room->room_code,
                'floor' => $room->floor,
                'status' => $room->status,
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
            'name' => 'max:255|required',
            'room_code' => 'max:255|required',
            'floor' => 'integer|max:255|required',
            'status' => 'integer|max:255',
        ]);
        $request->status === null ? $validatedData['status'] = 0 : $request->status;

        try {
            $room = RoomMaintenance::findOrFail($id);
            $room->update($validatedData);
            return response()->json(['message' => "$room->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $room = RoomMaintenance::find($id);
            $room->delete();
            return response()->json(['message' => "$room->name Berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $room = RoomMaintenance::find($id);
        $namaRuang = strtoupper($room->name);

        $item = Barang::where('room_id', $room->id)
            ->orWhere('ruang_pinjam', $room->id)
            ->get();

        // return dd($item);

        return view('pages.inventaris.rooms.show', [
            'title' => "$namaRuang",
            'rooms' => RoomMaintenance::orderBy('name')->get(),
            'ruang' => $room,
            'categories' => CategoryBarang::orderBy('name')->get(),
            'barang' => $item,
            'companies' => Company::all(),
            'templates' => TemplateBarang::orderBy('name')->get(),
            'jumlah' => count(Barang::where('room_id', $room->id)->get())
        ]);
    }
}
