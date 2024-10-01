<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SIMRS\Kepustakaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class KepustakaanController extends Controller
{
    public function index()
    {
        $kepustakaan = Kepustakaan::whereNull('parent_id')
            ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
            ->orderBy('name', 'asc')
            ->get();

        $organizations = Organization::all();
        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'organizations'));
    }

    public function showFolder($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        // Cari folder berdasarkan nama
        $folder = Kepustakaan::where('id', $id)
            ->where('type', 'folder') // Memastikan hanya folder
            ->firstOrFail();

        $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
            ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'folder'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'kategori' => 'required',
            'parent_id' => 'nullable',
            'organization_id' => 'nullable',
            'name' => 'required',
        ]);

        try {
            $store = Kepustakaan::create($validatedData);
            return response()->json(['message' => ' Folder/File ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
