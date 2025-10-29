<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\Document;
use App\Models\RS\MaterialApproval;
use App\Models\RS\ProjectBuildItem;
use App\Models\User;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MaterialApprovalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MaterialApproval::with(['submitter', 'reviewer', 'document', 'satuan'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('document_number', function ($row) {
                    return $row->document->document_number ?? '-';
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'Approved' => 'badge-success',
                        'Rejected' => 'badge-danger',
                        'Revision Required' => 'badge-warning',
                        'Submitted' => 'badge-primary',
                    ];
                    $badgeClass = $badges[$row->status] ?? 'badge-light';
                    return '<span class="badge ' . $badgeClass . '">' . $row->status . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-primary btn-sm mr-1 edit-btn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Hapus</button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $users = User::whereHas('employee', fn($q) => $q->where('is_active', true))->get();
        $documents = Document::where('is_latest', true)->orderBy('created_at', 'desc')->get();
        $satuans = WarehouseSatuanBarang::all();
        $projectBuildItems = ProjectBuildItem::where('is_active', true)->get();

        return view('app-type.rs.material_approvals.index', compact('users', 'documents', 'satuans', 'projectBuildItems'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'project_build_item_id' => 'required|exists:project_build_items,id',
            'quantity' => 'nullable|numeric|min:0',
            'satuan_id' => 'nullable|exists:warehouse_satuan_barang,id',
            'document_id' => 'nullable|exists:documents,id',
            'technical_specifications' => 'required|string',
            'status' => 'required|in:Submitted,Approved,Rejected,Revision Required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('material_photos', 'public');
        }

        MaterialApproval::create($request->all() + [
            'image_path' => $imagePath,
            'submitted_by' => Auth::id(),
        ]);

        return response()->json(['success' => 'Material approval saved successfully.']);
    }

    public function edit(MaterialApproval $materialApproval): JsonResponse
    {
        return response()->json($materialApproval);
    }

    public function update(Request $request, MaterialApproval $materialApproval): JsonResponse
    {
        $request->validate([
            'project_build_item_id' => 'required|exists:project_build_items,id',
            'quantity' => 'nullable|numeric|min:0',
            'satuan_id' => 'nullable|exists:warehouse_satuan_barang,id',
            'document_id' => 'nullable|exists:documents,id',
            'technical_specifications' => 'required|string',
            'status' => 'required|in:Submitted,Approved,Rejected,Revision Required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $materialApproval->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('material_photos', 'public');
        }

        $materialApproval->update($request->all() + ['image_path' => $imagePath]);

        return response()->json(['success' => 'Material approval updated successfully.']);
    }

    public function destroy(MaterialApproval $materialApproval): JsonResponse
    {
        if ($materialApproval->image_path) {
            Storage::disk('public')->delete($materialApproval->image_path);
        }
        $materialApproval->delete();

        return response()->json(['success' => 'Material approval deleted successfully.']);
    }

    /**
     * Display the dedicated review page for a Material Approval.
     */
    public function review(MaterialApproval $materialApproval)
    {
        // Eager load necessary relationships
        $materialApproval->load(['submitter.employee', 'document', 'satuan']);

        // Security check: ensure the logged-in user is the designated reviewer
        if (auth()->id() != $materialApproval->reviewed_by) {
            abort(403, 'UNAUTHORIZED ACTION. You are not the designated reviewer for this item.');
        }

        $satuans = WarehouseSatuanBarang::all();

        // Pass the data to the new review view
        return view('app-type.rs.material_approvals.review', [
            'material' => $materialApproval,
            'satuans' => $satuans,
        ]);
    }

    /**
     * Process the approval, rejection, or revision action from the review page.
     */
    public function processReview(Request $request, MaterialApproval $materialApproval): JsonResponse
    {
        // Security check
        if (auth()->id() != $materialApproval->reviewed_by) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Approved,Rejected,Revision Required',
            'remarks' => 'required_if:status,Rejected,Revision Required|nullable|string',
            'quantity' => 'nullable|numeric|min:0',
            'satuan_id' => 'nullable|exists:warehouse_satuan_barang,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Data yang akan diupdate
            $updateData = [
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];

            // Hanya update quantity dan satuan jika statusnya "Approved"
            if ($request->status === 'Approved') {
                $updateData['quantity'] = $request->quantity;
                $updateData['satuan_id'] = $request->satuan_id;
            }

            $materialApproval->update($updateData);

            return response()->json(['success' => 'Review has been submitted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }
}
