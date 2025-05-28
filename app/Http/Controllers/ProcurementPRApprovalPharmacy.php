<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseRequestPharmacy;
use Illuminate\Http\Request;

class ProcurementPRApprovalPharmacy extends Controller
{
    public function index(Request $request)
    {
        $query = ProcurementPurchaseRequestPharmacy::query()->with(["items"]);
        $filters = ["kode_pr", "approval", "tipe"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->has("status")) {
            if ($request->status == "all") {
                // $pr status is either "final" or "reviewed
                $query->whereIn('status', ['final', 'reviewed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('tanggal_pr')) {
            $dateRange = explode(' - ', $request->tanggal_pr);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_pr', [$startDate, $endDate]);
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
            $pr = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $pr = ProcurementPurchaseRequestPharmacy::where("status", "final")->get();
        }

        return view("pages.simrs.procurement.approval-pr.pharmacy", [
            "prs" => $pr
        ]);
    }

    public function edit(ProcurementPurchaseRequestPharmacy $procurementPurchaseRequestPharmacy, $id)
    {
        return view("pages.simrs.procurement.approval-pr.partials.popup-approve-pr-pharmacy", [
            "pr" => $procurementPurchaseRequestPharmacy->find($id)->first()
        ]);
    }

    public function update(Request $request, ProcurementPurchaseRequestPharmacy $procurementPurchaseRequestPharmacy, $id)
    {
        $validatedData = $request->validate([
            "tanggal_app" => "required|date",
            "user_id" => "required|exists:users,id",
            "keterangan_approval" => "nullable|string",
            "item_id" => "required|array",
            "item_id.*" => "required|integer",
            "approved_qty" => "required|array",
            "approved_qty.*" => "required|integer",
            "status_app" => "required|in:draft,final",
            "status_item" => "required|array",
            "status_item.*" => "required|in:approved,pending,rejected",
            "keterangan_item_app" => "nullable|array",
            "keterangan_item_app.*" => "nullable|string"
        ]);

        $pr = $procurementPurchaseRequestPharmacy->find($id)->first();
        if ($validatedData["status_app"] == 'final') {
            $pr->update(["status" => "reviewed"]);
        }

        $pr->update([
            "tanggal_app" => $validatedData["tanggal_app"],
            "app_user_id" => $validatedData["user_id"],
            "keterangan_approval" => $validatedData["keterangan_approval"]
        ]);
        $pr->save();

        foreach ($validatedData["item_id"] as $key => $id) {
            $item = $pr->items()->find($id);
            $item->update([
                "approved_qty" => $validatedData["approved_qty"][$key],
                "status" => $validatedData["status_item"][$key],
                "keterangan_approval" => $validatedData["keterangan_item_app"][$key]
            ]);
            $item->save();
        }

        return back()->with('success', 'Data berhasil disimpan');
    }

    public function print($id)
    {
        return view("pages.simrs.procurement.approval-pr.partials.pr-print-pharmacy", [
            "pr" => ProcurementPurchaseRequestPharmacy::find($id)
        ]);
    }
}
