<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderNonPharmacy;
use App\Models\ProcurementPurchaseOrderPharmacy;
use Illuminate\Http\Request;

class ProcurementPOApprovalCEO extends Controller
{
    public function index(Request $request)
    {
        $query1 = ProcurementPurchaseOrderNonPharmacy::query()->with(["items"]);
        $query2 = ProcurementPurchaseOrderPharmacy::query()->with(["items"]);
        $query1->where("approval", "approve");
        $query2->where("approval", "approve");
        $filters = ["kode_po", "tipe"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query1->where($filter, 'like', '%' . $request->$filter . '%');
                $query2->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->has("approval")) {
            if ($request->approval != "all") {
                $query1->where('approval_ceo', $request->approval);
                $query2->where('approval_ceo', $request->approval);
            }
        }

        if ($request->filled('tanggal_po')) {
            $dateRange = explode(' - ', $request->tanggal_po);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query1->whereBetween('tanggal_po', [$startDate, $endDate]);
                $query2->whereBetween('tanggal_po', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query1->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $query2->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $po1 = $query1->orderBy('created_at', 'asc')->get();
            $po2 = $query2->orderBy('created_at', 'asc')->get();
            $po1 = $po1->all();
            $po2 = $po2->all();
            $po = collect(array_merge($po1, $po2));
            $po = $po->sortBy('created_at');
        } else {
            // Return all data if no filter is applied
            $po1 = ProcurementPurchaseOrderNonPharmacy::where("approval_ceo", "unreviewed")->where("approval", "approve")->get();
            $po2 = ProcurementPurchaseOrderPharmacy::where("approval_ceo", "unreviewed")->where("approval", "approve")->get();
            $po1 = $po1->all();
            $po2 = $po2->all();
            $po = collect(array_merge($po1, $po2));
            $po = $po->sortBy('created_at');
        }
        return view("pages.simrs.procurement.approval-po.ceo", [
            "pos" => $po
        ]);
    }

    public function edit($type, $id)
    {
        if ($type == "np") {
            $po = ProcurementPurchaseOrderNonPharmacy::find($id);
        } else {
            $po = ProcurementPurchaseOrderPharmacy::find($id);
        }

        return view("pages.simrs.procurement.approval-po.partials.popup-approve-po-ceo", [
            "po" => $po,
            "type" => $type
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "tanggal_app_ceo" => "required|date",
            "user_id" => "required|exists:users,id",
            "keterangan_approval_ceo" => "nullable|string",
            "status_app_ceo" => "required|in:approve,revision,reject",
            "type" => "required|in:np,p"
        ]);

        if($validatedData["type"] == "np"){
            $po = ProcurementPurchaseOrderNonPharmacy::find($id)->first();
        } else{
            $po = ProcurementPurchaseOrderPharmacy::find($id)->first();
        }

        if ($validatedData["status_app_ceo"] == 'revision') {
            $po->update(["status" => "revision"]);
        }

        $po->update([
            "approval_ceo" => $validatedData["status_app_ceo"],
            "tanggal_app_ceo" => $validatedData["tanggal_app_ceo"],
            "ceo_app_user_id" => $validatedData["user_id"],
            "keterangan_approval_ceo" => $validatedData["keterangan_approval_ceo"]
        ]);
        $po->save();

        return back()->with('success', 'Data berhasil disimpan');
    }
}
