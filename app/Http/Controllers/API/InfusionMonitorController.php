<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InfusionMonitor;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class InfusionMonitorController extends Controller
{
    public function index(Registration $registration)
    {
        $data = InfusionMonitor::where('registration_id', $registration->id)->orderBy('waktu_infus', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('waktu_infus', function ($row) {
                return [
                    'tanggal' => $row->waktu_infus->isoFormat('D MMMM YYYY'),
                    'jam' => $row->waktu_infus->isoFormat('HH:mm'),
                ];
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn" title="Edit"><i class="mdi mdi-pencil"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="mdi mdi-delete"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'waktu_infus' => 'required|date',
            'kolf_ke' => 'nullable|string|max:50',
            'jenis_cairan' => 'required|string',
            'keterangan' => 'nullable|string',
            'cairan_masuk' => 'required|numeric',
            'cairan_sisa' => 'nullable|numeric',
            'nama_perawat' => 'required|string|max:255',
        ]);
        $validated['user_id'] = Auth::id();
        InfusionMonitor::create($validated);
        return response()->json(['success' => 'Data infus berhasil disimpan.']);
    }

    public function edit(InfusionMonitor $monitor)
    {
        return response()->json($monitor);
    }

    public function update(Request $request, InfusionMonitor $monitor)
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'waktu_infus' => 'required|date',
            'kolf_ke' => 'nullable|string|max:50',
            'jenis_cairan' => 'required|string',
            'keterangan' => 'nullable|string',
            'cairan_masuk' => 'required|numeric',
            'cairan_sisa' => 'nullable|numeric',
            'nama_perawat' => 'required|string|max:255',
        ]);
        $validated['user_id'] = Auth::id();
        $monitor->update($validated);
        return response()->json(['success' => 'Data infus berhasil diperbarui.']);
    }

    public function destroy(InfusionMonitor $monitor)
    {
        $monitor->delete(); // Soft delete
        return response()->json(['success' => 'Data infus berhasil dihapus.']);
    }
}
