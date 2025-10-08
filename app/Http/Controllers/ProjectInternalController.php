<?php

namespace App\Http\Controllers;

use App\Models\ProjectInternal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProjectInternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan daftar user untuk dropdown
        $users = User::where('is_active', 1)->orderBy('name')->get();
        return view('pages.project-internal.index', compact('users'));
    }

    /**
     * Provide data for DataTables.
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectInternal::with('user')->latest('id')->select('project_internals.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('datetime', function ($row) {
                    return Carbon::parse($row->datetime)->format('d-m-Y H:i');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge badge-warning">Pending</span>';
                    } elseif ($row->status == 'on-progress') {
                        return '<span class="badge badge-info">On Progress</span>';
                    } elseif ($row->status == 'done') {
                        return '<span class="badge badge-success">Done</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . ucfirst($row->status) . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex">';
                    $btn .= '<button type="button" class="btn btn-sm btn-primary btn-edit mr-1" data-id="' . $row->id . '"><i class="fas fa-edit"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>';
                    if ($row->attachment) {
                        $btn .= '<button type="button" class="btn btn-sm btn-info btn-view-attachment ml-1" data-url="' . asset('storage/' . $row->attachment) . '"><i class="fas fa-image"></i></button>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'datetime' => 'required|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            'status' => 'required|in:pending,on-progress,done',
            'done_at' => 'nullable|date|required_if:status,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('projects', 'public');
            $data['attachment'] = $filePath;
        }

        if ($data['status'] == 'done' && empty($data['done_at'])) {
            $data['done_at'] = now();
        }

        ProjectInternal::create($data);

        return response()->json(['success' => true, 'message' => 'Proyek berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = ProjectInternal::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Proyek tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $project]);
    }

    /**
     * Endpoint to get the attachment for modal view.
     */
    public function attachment($id)
    {
        $project = ProjectInternal::find($id);
        if (!$project || !$project->attachment) {
            return response()->json(['success' => false, 'message' => 'Lampiran tidak ditemukan.'], 404);
        }

        $url = asset('storage/' . $project->attachment);
        $ext = strtolower(pathinfo($project->attachment, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);

        return response()->json([
            'success' => true,
            'url' => $url,
            'is_image' => $isImage,
            'filename' => basename($project->attachment),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = ProjectInternal::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Proyek tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'datetime' => 'required|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,on-progress,done',
            'done_at' => 'nullable|date|required_if:status,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle file update
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($project->attachment) {
                Storage::disk('public')->delete($project->attachment);
            }
            // Simpan file baru
            $filePath = $request->file('attachment')->store('projects', 'public');
            $data['attachment'] = $filePath;
        }

        if ($data['status'] == 'done' && empty($data['done_at'])) {
            $data['done_at'] = now();
        } elseif ($data['status'] != 'done') {
            $data['done_at'] = null;
        }

        $project->update($data);

        return response()->json(['success' => true, 'message' => 'Proyek berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = ProjectInternal::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Proyek tidak ditemukan.'], 404);
        }

        // Hapus file attachment dari storage sebelum menghapus record
        if ($project->attachment) {
            Storage::disk('public')->delete($project->attachment);
        }

        $project->delete();

        return response()->json(['success' => true, 'message' => 'Proyek berhasil dihapus.']);
    }
}
