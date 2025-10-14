<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderPharmacy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProcurementPOApprovalPharmacy extends Controller
{
    public function index(Request $request)
    {
        // Jika AJAX, proses datatables
        if ($request->ajax()) {
            // Query dasar, eager load user dan app_user dengan employee
            $query = ProcurementPurchaseOrderPharmacy::with(['supplier', 'user.employee', 'app_user.employee'])
                ->where('status', 'final')
                ->select('procurement_purchase_order_pharmacy.*');

            // Filter kode PO
            if ($request->filled('kode_po')) {
                $query->where('kode_po', 'like', '%' . $request->kode_po . '%');
            }

            // Filter tipe
            if ($request->filled('tipe')) {
                $query->where('tipe', $request->tipe);
            }

            // Filter berdasarkan nama barang (di relasi items)
            if ($request->filled('nama_barang')) {
                $query->whereHas('items', function ($q) use ($request) {
                    $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
                });
            }

            // Filter range tanggal PO
            if ($request->filled('tanggal_po')) {
                $dateRange = explode(' - ', $request->tanggal_po);
                if (count($dateRange) === 2) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->endOfDay();
                    $query->whereBetween('tanggal_po', [$startDate, $endDate]);
                }
            }

            // Filter approval: default ke 'unreviewed' jika tidak ada/semua
            $approvalStatus = $request->input('approval', 'unreviewed');
            if ($approvalStatus !== 'all') {
                $query->where('approval', $approvalStatus);
            }

            return DataTables::of($query)
                ->addColumn('detail', function ($row) {
                    return '<button class="btn btn-primary btn-xs btn-detail" data-id="' . $row->id . '"><i class="fal fa-eye"></i></button>';
                })
                ->editColumn('tanggal_po', function ($row) {
                    return tgl($row->tanggal_po);
                })
                ->editColumn('tanggal_app', function ($row) {
                    return $row->tanggal_app ? tgl($row->tanggal_app) : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('supplier_name', function ($row) {
                    return $row->supplier->nama ?? 'N/A';
                })
                ->addColumn('user_app_name', function ($row) {
                    return $row->app_user->employee->fullname ?? '<span class="text-muted">N/A</span>';
                })
                ->editColumn('tipe', function ($row) {
                    return ucfirst($row->tipe);
                })
                ->editColumn('nominal', function ($row) {
                    return rp($row->nominal);
                })
                ->addColumn('status_approval', function ($row) {
                    switch ($row->approval) {
                        case 'approve':
                            return '<span class="badge badge-success fs-md">Approved</span>';
                        case 'reject':
                            return '<span class="badge badge-danger fs-md">Rejected</span>';
                        case 'revision':
                            return '<span class="badge badge-warning fs-md">Revision</span>';
                        default:
                            return '<span class="badge badge-secondary fs-md">Unreviewed</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    if ($row->approval == 'unreviewed') {
                        return '<button class="btn btn-warning btn-sm btn-review" data-id="' . $row->id . '"><i class="fal fa-edit mr-1"></i> Review</button>';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->rawColumns(['detail', 'tanggal_app', 'user_app_name', 'status_approval', 'action'])
                ->make(true);
        }

        // Jika bukan AJAX, hanya tampilkan view
        return view("pages.simrs.procurement.approval-po.pharmacy");
    }

    public function edit(ProcurementPurchaseOrderPharmacy $procurementPurchaseOrderPharmacy, $id)
    {
        return view("pages.simrs.procurement.approval-po.partials.popup-approve-po-pharmacy", [
            "po" => $procurementPurchaseOrderPharmacy->findorfail($id)
        ]);
    }

    public function update(Request $request, ProcurementPurchaseOrderPharmacy $procurementPurchaseOrderPharmacy, $id)
    {
        $validatedData = $request->validate([
            "tanggal_app" => "required|date",
            "user_id" => "required|exists:users,id",
            "keterangan_approval" => "nullable|string",
            "status_app" => "required|in:approve,revision,reject"
        ]);

        $po = $procurementPurchaseOrderPharmacy->findorfail($id);

        if ($validatedData["status_app"] == 'revision') {
            $po->update(["status" => "revision"]);
        }

        $po->update([
            "approval" => $validatedData["status_app"],
            "tanggal_app" => $validatedData["tanggal_app"],
            "app_user_id" => $validatedData["user_id"],
            "keterangan_approval" => $validatedData["keterangan_approval"]
        ]);
        $po->save();

        return back()->with('success', 'Data berhasil disimpan');
    }
}
