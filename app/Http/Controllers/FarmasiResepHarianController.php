<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResepHarian;
use App\Models\FarmasiResepHarianItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiResepHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private function generate_rh_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = FarmasiResepHarian::withTrashed()->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return "RH" . $year . $month . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            "user_id" => "required|exists:users,id",
            "registration_id" => "required|exists:registrations,id",
            "doctor_id" => "required|exists:doctors,id",
            "gudang_id" => "required|exists:warehouse_master_gudang,id",
            "resep_manual" => "nullable|string"
        ]);
        $data["kode_resep"] = $this->generate_rh_code();

        $data2 = $request->validate([
            "barang_id.*" => "exists:warehouse_barang_farmasi,id",
            "qty_perhari.*" => "integer",
            "qty_hari.*" => "integer",
            "signa.*" => "string"
        ]);

        DB::beginTransaction();
        try {
            $rh = FarmasiResepHarian::create($data);

            foreach ($data2["barang_id"] as $key => $barang_id) {
                FarmasiResepHarianItems::create([
                    "rh_id" => $rh->id,
                    "barang_id" => $barang_id,
                    "signa" => $data2["signa"][$key],
                    "qty_perhari" => $data2["qty_perhari"][$key],
                    "qty_hari" => $data2["qty_hari"][$key],
                ]);
            }

            DB::commit();
            return back()->with("success", "Resep harian berhasil ditambahkan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiResepHarian $farmasiResepHarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiResepHarian $farmasiResepHarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiResepHarian $farmasiResepHarian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiResepHarian $farmasiResepHarian)
    {
        //
    }
}
