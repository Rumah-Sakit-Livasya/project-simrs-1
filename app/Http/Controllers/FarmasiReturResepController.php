<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResepItems;
use App\Models\FarmasiReturResep;
use App\Models\FarmasiReturResepItems;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use App\Models\StoredBarangFarmasi;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
use App\Services\CreateStockArguments;
use App\Services\GoodsStockService;
use App\Services\GoodsType;
use App\Services\IncreaseDecreaseStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiReturResepController extends Controller
{
    protected GoodsStockService $goodsStockService;

    public function __construct(GoodsStockService $goodsStockService)
    {
        $this->goodsStockService = $goodsStockService;
        $this->goodsStockService->controller = $this::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $relations = ["items", "patient", "gudang", "registration"];
        $query = FarmasiReturResep::query()->with($relations);
        $filter = false;

        if ($request->filled('tanggal')) {
            $dateRange = explode(' - ', $request->tanggal);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_retur', [$startDate, $endDate]);
            }
            $filter = true;
        }

        if ($request->filled('nama_pasien')) {
            $query->whereHas("patient", function ($q) use ($request) {
                $q->where("name", "like", "%{$request->nama_pasien}%");
            });
            $filter = true;
        }

        if ($request->filled("gudang_id")) {
            $query->where("gudang_id", $request->gudang_id);
            $filter = true;
        }

        if ($filter) {
            $returs = $query->get();
        } else {
            $returs = FarmasiReturResep::with($relations)->whereDate('created_at', today())->get();
        }

        return view("pages.simrs.farmasi.retur-resep.index", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get(),
            'returs' => $returs
        ]);
    }

    public function getRegistrations(int $patient_id)
    {
        $registrations = Registration::with(['patient', 'patient.bed', 'patient.bed.room', 'kelas_rawat'])->where("patient_id", $patient_id)->get()->all();
        return response()->json($registrations);
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

    public function getItemRegistration(int $id)
    {
        $items = FarmasiResepItems::with([
            'stored',
            'stored.pbi',
            'stored.gudang',
            'resep',
            'resep.registration'
        ])
            ->whereHas('resep', function ($q) use ($id) {
                $q->where("registration_id", $id);
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
        $month = $date->format('m');

        $count = FarmasiReturResep::withTrashed()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return "RF" . $year . $month . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            "user_id" => "required|exists:users,id",
            "tanggal_retur" => "required|date",
            "patient_id" => "required|exists:patients,id",
            "registration_id" => "required|exists:registrations,id",
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
            $user = User::findOrFail($data["user_id"]);
            $patient = Patient::findOrFail($data["patient_id"]);
            $warehouse = WarehouseMasterGudang::findOrFail($data["gudang_id"]);

            $rr = FarmasiReturResep::create([
                "user_id" => $data["user_id"],
                "tanggal_retur" => $data["tanggal_retur"],
                "patient_id" => $data["patient_id"],
                "registration_id" => $data["registration_id"],
                "gudang_id" => $data["gudang_id"],
                "kode_retur" => $this->generate_rf_code(),
                "keterangan" => $data["keterangan"],
                "total" => $data["nominal"]
            ]);

            foreach ($data["item_id"] as $key => $id) {
                $item = FarmasiResepItems::findOrFail($id);
                $item->update([
                    "returned_qty" => $item->returned_qty + $data["qty"][$key],
                ]);

                if ($data["gudang_id"] != $item->stored->gudang_id) {
                    // different warehouse than where the item was taken
                    // check if there's any StoredBarangFarmasi with pbi_id == $item->stored->pbi_id
                    // and gudang_id == $data["gudang_id"]
                    $stored = StoredBarangFarmasi::where([
                        "pbi_id" => $item->stored->pbi_id,
                        "gudang_id" => $data["gudang_id"],
                    ])->first();

                    if ($stored) {
                        // update stored
                        $args = new  IncreaseDecreaseStockArguments($user, $rr, $stored, $data["qty"][$key], "RETUR RESEP PS: " . $patient->name);
                        $this->goodsStockService->increaseStock($args);
                    } else {
                        // create new stored
                        $args = new CreateStockArguments($user, $rr, GoodsType::Pharmacy, $warehouse, $item->stored->pbi, "RETUR RESEP PS: " . $patient->name, $data["qty"][$key]);
                        $this->goodsStockService->createStock($args);
                    }
                } else {
                    // same warehouse
                    $args = new  IncreaseDecreaseStockArguments($user, $rr, $item->stored, $data["qty"][$key], "RETUR RESEP PS: " . $patient->name);
                    $this->goodsStockService->increaseStock($args);
                }

                FarmasiReturResepItems::create([
                    "retur_id" => $rr->id,
                    "ri_id" => $id,
                    "qty" => $data["qty"][$key],
                    "subtotal" => $data["subtotal"][$key]
                ]);
            }

            DB::commit();
            return back()->with('success', "Data berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function print(int $id)
    {
        $retur = FarmasiReturResep::findOrFail($id);

        return view("pages.simrs.farmasi.retur-resep.partials.print-rr", compact('retur'));
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
    public function destroy(FarmasiReturResep $farmasiReturResep, int $id)
    {
        DB::beginTransaction();
        try {
            $rr = $farmasiReturResep::findOrFail($id);
            $user = User::findorFail(auth()->user()->id);
            $rr->delete();

            foreach ($rr->items as $item) {
                $item->ri->update([
                    "returned_qty" => $item->ri->returned_qty - $item->qty
                ]);

                $args = new IncreaseDecreaseStockArguments($user, $rr, $item->ri->stored, $item->qty, "BATAL RETUR RESEP PS: " . $rr->patient->name);
                $this->goodsStockService->decreaseStock($args);

                $item->delete();
            }

            DB::commit();
            // return JSON 200 ok
            return response()->json([
                'message' => 'Data berhasil dihapus',
                'success' => true,
                'data' => $rr,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return error in JSON
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
