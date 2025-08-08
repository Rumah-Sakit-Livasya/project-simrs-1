<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderNonPharmacy;
use Illuminate\Http\Request;

class ProcurementPOApprovalNonPharmacy extends Controller
{
    public function index(Request $request)
    {
        $query = ProcurementPurchaseOrderNonPharmacy::query()->with(["items"]);
        $filters = ["kode_po", "tipe"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->has("approval")) {
            if ($request->approval != "all") {
                $query->where('approval', $request->approval);
            }
        }

        if ($request->filled('tanggal_po')) {
            $dateRange = explode(' - ', $request->tanggal_po);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_po', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $po = $query->where("status", "final")->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $po = ProcurementPurchaseOrderNonPharmacy::where("approval", "unreviewed")->where("status", "final")->get();
        }

        return view("pages.simrs.procurement.approval-po.non-pharmacy", [
            "pos" => $po
        ]);
    }

    public function edit(ProcurementPurchaseOrderNonPharmacy $procurementPurchaseOrderNonPharmacy, $id)
    {
        return view("pages.simrs.procurement.approval-po.partials.popup-approve-po-non-pharmacy", [
            "po" => $procurementPurchaseOrderNonPharmacy->findorfail($id)
        ]);
    }

    public function update(Request $request, ProcurementPurchaseOrderNonPharmacy $procurementPurchaseOrderNonPharmacy, $id)
    {
        $validatedData = $request->validate([
            "tanggal_app" => "required|date",
            "user_id" => "required|exists:users,id",
            "keterangan_approval" => "nullable|string",
            "status_app" => "required|in:approve,revision,reject"
        ]);

        $po = $procurementPurchaseOrderNonPharmacy->findorfail($id);

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
