<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use App\Models\Organization;
use Illuminate\Http\Request;

class RoomMaintenanceController extends Controller
{
    public function index()
    {
        $rooms = RoomMaintenance::orderBy('created_at', 'desc')->get();
        $organizations = Organization::orderBy('created_at', 'desc')->get();
        return view('app-type.logistik.rooms.index', compact('rooms', 'organizations'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'room_code' => 'max:255|required',
            'floor' => 'integer|max:255|required',
            'organization_id' => 'required|array', // Ensure organization_id is an array
            'organization_id.*' => 'integer|exists:organizations,id', // Validate each organization ID
        ]);

        try {
            $store = RoomMaintenance::create($validatedData);
            // Sync the organizations with the room
            RoomMaintenance::where('id', $store->id)->first()->organizations()->sync($request->organization_id);
            return response()->json(['message' => "$store->name Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRoom($id)
    {
        try {
            // Eager load organizations and specify the columns to avoid ambiguity
            $room = RoomMaintenance::with(['organizations' => function ($query) {
                $query->select('organizations.id', 'name'); // Specify the columns you want from organizations
            }])->findOrFail($id);

            return response()->json([
                'name' => $room->name,
                'room_code' => $room->room_code,
                'floor' => $room->floor,
                'status' => $room->status,
                'organization_ids' => $room->organizations()->pluck('organizations.id')->toArray(), // Return organization IDs
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan pada server', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        // return dd($request);
        $validatedData = $request->validate([
            'name' => 'max:255|required',
            'room_code' => 'max:255|required',
            'floor' => 'integer|max:255|required',
            'status' => 'integer|max:255',
            'organization_id' => 'required|array', // Ensure organization_id is an array
            'organization_id.*' => 'integer|exists:organizations,id', // Validate each organization ID
        ]);

        // $request->status === null ? $validatedData['status'] = 0 : $request->status;
        // Set status to 0 if not provided
        $validatedData['status'] = $request->status ?? 0;

        try {
            // Find the room by ID
            $room = RoomMaintenance::findOrFail($id);

            // Update the room details
            $room->update($validatedData);

            // Sync the organizations with the room
            $room->organizations()->sync($request->organization_id);

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
        $allRoom = RoomMaintenance::orderBy('name', 'asc')->get();

        $item = Barang::where('room_id', $room->id)
            ->orWhere('ruang_pinjam', $room->id)
            ->get();

        // return dd($item);

        return view('app-type.logistik.rooms.show', [
            'title' => "$namaRuang",
            'rooms' => RoomMaintenance::orderBy('name')->get(),
            'ruang' => $room,
            'allRoom' => $allRoom,
            'categories' => CategoryBarang::orderBy('name')->get(),
            'barang' => $item,
            'companies' => Company::all(),
            'templates' => TemplateBarang::orderBy('name')->get(),
            'jumlah' => count(Barang::where('room_id', $room->id)->get())
        ]);
    }

    public function printLabel(Request $request)
    {
        $barang = Barang::where('room_id', $request->room_id)->get();
        $room_id = RoomMaintenance::where('id', $request->room_id)->first('id')->id;
        return view('app-type.logistik.rooms.print-label', [
            'items' => $barang,
            'room_id' => $room_id
        ]);
    }
}
