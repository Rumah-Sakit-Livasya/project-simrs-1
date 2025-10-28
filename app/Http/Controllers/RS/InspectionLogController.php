<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\InspectionLog;
use App\Models\RS\MaterialApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class InspectionLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InspectionLog::with(['inspector', 'materialApproval'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('inspection_date', function ($row) {
                    return $row->inspection_date->format('d-m-Y');
                })
                ->addColumn('material_name', function ($row) {
                    return $row->materialApproval->material_name ?? '-';
                })
                ->editColumn('result', function ($row) {
                    $badges = [
                        'Pass' => 'badge-success',
                        'Fail' => 'badge-danger',
                        'Correction Required' => 'badge-warning',
                    ];
                    $badgeClass = $badges[$row->result] ?? 'badge-light';
                    return '<span class="badge ' . $badgeClass . '">' . $row->result . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-primary btn-sm mr-1 edit-btn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Hapus</button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action', 'result'])
                ->make(true);
        }

        $users = User::whereHas('employee', fn($q) => $q->where('is_active', true))->get();
        // Ambil hanya material yang sudah di-approve untuk diinspeksi
        $approvedMaterials = MaterialApproval::with('document') // <-- Eager load relasi 'document'
            ->where('status', 'Approved')
            ->get();

        return view('app-type.rs.inspection_logs.index', compact('users', 'approvedMaterials'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'inspection_type' => 'required|in:Incoming Material,Work In Progress',
            'inspection_date' => 'required|date',
            'material_approval_id' => 'required_if:inspection_type,Incoming Material|nullable|exists:material_approvals,id',
            'reference_document' => 'nullable|string|max:255',
            'description' => 'required|string',
            'result' => 'required|in:Pass,Fail,Correction Required',
            'notes' => 'nullable|string',
        ]);

        InspectionLog::create($request->all() + ['inspected_by' => Auth::id()]);

        return response()->json(['success' => 'Log inspeksi berhasil disimpan.']);
    }

    public function edit(InspectionLog $inspectionLog): JsonResponse
    {
        return response()->json($inspectionLog);
    }

    public function update(Request $request, InspectionLog $inspectionLog): JsonResponse
    {
        $request->validate([
            'inspection_type' => 'required|in:Incoming Material,Work In Progress',
            'inspection_date' => 'required|date',
            'material_approval_id' => 'required_if:inspection_type,Incoming Material|nullable|exists:material_approvals,id',
            'reference_document' => 'nullable|string|max:255',
            'description' => 'required|string',
            'result' => 'required|in:Pass,Fail,Correction Required',
            'notes' => 'nullable|string',
        ]);

        $inspectionLog->update($request->all());

        return response()->json(['success' => 'Log inspeksi berhasil diperbarui.']);
    }

    public function destroy(InspectionLog $inspectionLog): JsonResponse
    {
        $inspectionLog->delete();
        return response()->json(['success' => 'Log inspeksi berhasil dihapus.']);
    }
}
