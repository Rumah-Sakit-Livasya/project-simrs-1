<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResepItems;
use App\Models\FarmasiReturResep;
use App\Models\SIMRS\Patient;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiReturResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.farmasi.retur-resep.index", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.simrs.farmasi.retur-resep.retur", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get(),
            "patients" => Patient::all()
        ]);
    }

    public function getItemPatient(int $id)
    {
        $items = FarmasiResepItems::with([
            'stored',
            'stored.pbi',
            'stored.gudang',
            'resep',
            'resep.registration'
        ])
            ->whereHas('resep.registration', function ($q) use ($id) {
                $q->where("patient_id", $id);
            })
            ->where('racikan_id', null)
            ->where('tipe', 'obat')
            ->get();

        return view("pages.simrs.farmasi.retur-resep.partials.table-items-rr", compact('items'));
    }

    private function generate_rf_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');

        $count = FarmasiReturResep::whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return "RF" . $year . "-" . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

        $data = $request->validate([
            "user_id" => "required|exists:users,id",
            "tanggal_retur" => "required|date",
            "patient_id" => "required|exists:patients,id",
            "gudang_id" => "required|exists:warehouse_master_gudang,id",
            "nominal" => "required|integer",
            "keterangan" => "nullable|string",
            "item_id.*" => "exists:farmasi_resep_items,id",
            "hna.*" => "integer",
            "subtotal.*" => "integer",
            "qty.*" => "integer"
        ]);

        DB::beginTransaction();

        try {
            FarmasiReturResep::create([
                "tanggal_retur" => $data["tanggal_retur"],
                "patient_id" => $data["patient_id"],
                "gudang_id" => $data["gudang_id"],

            ]);

            DB::commit();
            return back()->with('success', "Data berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiReturResep $farmasiReturResep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiReturResep $farmasiReturResep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiReturResep $farmasiReturResep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiReturResep $farmasiReturResep)
    {
        //
    }
}
