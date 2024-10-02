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
        $breadcrumbs = collect();
        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'organizations'));
    }

    public function showFolder($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        // Cari folder berdasarkan nama
        $folder = Kepustakaan::where('id', $id)
            ->where('type', 'folder') // Memastikan hanya folder
            ->firstOrFail();

        $breadcrumbs = getBreadcrumbs($folder);
        if (auth()->user()->can('master kepustakaan')) {
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->where('organization_id', auth()->user()->employee->organization_id)
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();
        }

        if (auth()->user()->hasRole('super admin') || auth()->user()->can('master kepustakaan')) {
            $organizations = Organization::all();
        } else {
            $organizations = Organization::where('id', auth()->user()->employee->organization_id)->first();
        }

        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'folder', 'organizations'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'kategori' => 'required',
            'parent_id' => 'nullable',
            'organization_id' => 'nullable',
            'name' => 'required',
            'size' => 'nullable',
            'file' => 'nullable',
        ]);

        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $fileName = $request->name . '.' . $file->getClientOriginalExtension();
            $path = 'kepustakaan/' . \Str::slug($request->kategori);
            $pathFix = $file->storeAs($path, $fileName, 'public');
            $validatedData['file'] = $fileName;
        }

        try {
            $store = Kepustakaan::create($validatedData);
            return response()->json(['message' => ' Folder/File ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
