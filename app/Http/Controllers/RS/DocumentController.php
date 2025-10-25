<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\Document;
use App\Models\RS\DocumentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    /**
     * Menampilkan halaman utama dan menghandle request DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Document::where('is_latest', true)
                ->with(['uploader', 'personInCharge', 'documentType'])
                ->select('documents.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $viewBtn = '<button class="btn btn-info btn-sm mr-1 view-btn" data-id="' . $row->id . '">Detail</button>';
                    $editBtn = '<button class="btn btn-primary btn-sm mr-1 edit-btn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Hapus</button>';
                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->documentType->name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'Disetujui' => 'badge-success',
                        'Revisi' => 'badge-danger',
                        'Direview' => 'badge-warning',
                        'Diterima' => 'badge-info',
                        'Diajukan' => 'badge-primary',
                        'Dibalas' => 'badge-secondary',
                    ];
                    $badgeClass = $badges[$row->status] ?? 'badge-light';
                    return '<span class="badge ' . $badgeClass . '">' . $row->status . '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $users = User::whereHas('employee', fn($q) => $q->where('is_active', true))->get();
        $documentTypes = DocumentType::all();

        return view('app-type.rs.documents.index', compact('users', 'documentTypes'));
    }

    /**
     * Menyimpan dokumen baru.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document_number' => 'required|string|unique:documents,document_number',
            'document_type_id' => 'required|exists:document_types,id',
            'status' => 'required|in:Diajukan,Diterima,Direview,Revisi,Disetujui,Dibalas',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,dwg,xls,xlsx,doc,docx|max:10240',
            'person_in_charge_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('project_documents', 'public');

        Document::create([
            'title' => $request->title,
            'document_number' => $request->document_number,
            'description' => $request->description,
            'document_type_id' => $request->document_type_id,
            'status' => $request->status,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploader_id' => Auth::id(),
            'person_in_charge_id' => $request->person_in_charge_id,
        ]);

        return response()->json(['success' => 'Dokumen baru berhasil disimpan.']);
    }

    /**
     * Mengambil data untuk form edit.
     */
    public function edit(Document $document): JsonResponse
    {
        return response()->json($document);
    }

    /**
     * Memperbarui dokumen.
     * Jika ada file baru, akan membuat revisi baru.
     * Jika tidak ada file, hanya memperbarui metadata.
     */
    public function update(Request $request, Document $document): JsonResponse
    {

        $rules = [
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'status' => 'required|in:Diajukan,Diterima,Direview,Revisi,Disetujui,Dibalas',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg,xls,xlsx,doc,docx|max:10240',
            'person_in_charge_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ];

        // Beri validasi unique ONLY jika user mengirim/mengubah document_number DAN isinya berbeda dari milik dokumen saat ini
        if (
            $request->has('document_number') &&
            $request->input('document_number') !== $document->document_number
        ) {
            $rules['document_number'] = [
                'required',
                'string',
                Rule::unique('documents')->ignore($document->id),
            ];
        }

        $messages = [
            'document_number.unique' => 'Nomor dokumen sudah digunakan.',
        ];

        $validated = $request->validate($rules, $messages);

        if ($request->hasFile('file')) {
            // Hapus file lama bila ada
            if ($document->file_path) {
                Storage::disk('private')->delete($document->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('project_documents', ['disk' => 'private']);
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            dd([
                'filePath' => $filePath,
                'fileName' => $fileName,
                'fileSize' => $fileSize,
            ]);

            try {
                DB::transaction(function () use ($document, $validated, $filePath, $fileName, $fileSize) {
                    $document->update(['is_latest' => false]);

                    Document::create([
                        'title' => $validated['title'],
                        'document_number' => $validated['document_number'] ?? $document->document_number,
                        'description' => $validated['description'] ?? null,
                        'document_type_id' => $validated['document_type_id'],
                        'status' => $validated['status'],
                        'person_in_charge_id' => $validated['person_in_charge_id'] ?? null,
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'file_size' => $fileSize,
                        'uploader_id' => Auth::id(),
                        'parent_id' => $document->parent_id ?? $document->id,
                        'version' => $document->version + 1,
                        'is_latest' => true,
                    ]);
                });
            } catch (\Exception $e) {
                return response()->json(['message' => 'Gagal membuat revisi: ' . $e->getMessage()], 500);
            }

            return response()->json(['success' => 'Revisi dokumen berhasil diupload.']);
        } else {
            // Ambil field yang mau diupdate
            $fieldsToUpdate = [
                'title',
                'document_type_id',
                'status',
                'person_in_charge_id',
                'description'
            ];
            // Hanya update document_number jika dikirim dan berubah
            if (
                $request->has('document_number') &&
                $request->input('document_number') !== $document->document_number
            ) {
                $fieldsToUpdate[] = 'document_number';
            }

            $document->update($request->only($fieldsToUpdate));

            return response()->json(['success' => 'Data dokumen berhasil diperbarui.']);
        }
    }

    /**
     * Shows document details for preview.
     */
    public function show(Document $document): JsonResponse
    {
        $fileUrl = Storage::disk('public')->url($document->file_path);
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        return response()->json([
            'title' => $document->title,
            'file_url' => $fileUrl,
            'file_name' => $document->file_name,
            'extension' => strtolower($extension)
        ]);
    }

    /**
     * Menampilkan halaman preview dokumen untuk popup window.
     */
    public function preview(Document $document)
    {
        $fileUrl = Storage::disk('public')->url($document->file_path);
        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));

        // Kirim data ke view khusus untuk preview
        return view('app-type.rs.documents.preview', [
            'document' => $document,
            'fileUrl' => $fileUrl,
            'extension' => $extension,
        ]);
    }

    /**
     * Menghapus dokumen.
     */
    public function destroy(Document $document): JsonResponse
    {
        $document->delete();
        return response()->json(['success' => 'Dokumen berhasil dihapus.']);
    }
}
