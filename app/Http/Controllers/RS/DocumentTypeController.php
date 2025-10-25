<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\DocumentType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DocumentType::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-primary btn-sm mr-1 edit-btn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Hapus</button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('app-type.rs.document_types.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:document_types,name,' . $request->type_id,
            'description' => 'nullable|string',
        ]);

        DocumentType::updateOrCreate(
            ['id' => $request->type_id],
            ['name' => $request->name, 'description' => $request->description]
        );

        return response()->json(['success' => 'Tipe dokumen berhasil disimpan.']);
    }

    public function edit($id)
    {
        $type = DocumentType::findOrFail($id);
        return response()->json($type);
    }

    public function destroy($id)
    {
        // Tambahkan validasi, jangan biarkan hapus jika sudah dipakai di tabel documents
        $type = DocumentType::withCount('documents')->findOrFail($id);

        if ($type->documents_count > 0) {
            return response()->json(['error' => 'Tipe ini tidak bisa dihapus karena sudah digunakan.'], 422);
        }

        $type->delete();
        return response()->json(['success' => 'Tipe dokumen berhasil dihapus.']);
    }
}
