<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\FarmasiResepElektronik;
use App\Models\FarmasiResepItems;
use App\Models\FarmasiResepResponse;
use App\Models\FarmasiSigna;
use App\Models\FarmasiTelaahResep;
use App\Models\RegistrationOTC;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\StoredBarangFarmasi;
use App\Models\User;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Services\GoodsStockService;
use App\Services\IncreaseDecreaseStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiResepController extends Controller
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
    public function index()
    {
        return view("pages.simrs.farmasi.transaksi-resep.index", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get()
        ]);
    }


    public function get_batch(int $gudang_id, int $barang_id)
    {
        $query = StoredBarangFarmasi::query()
            ->with(["pbi", "pbi.item", "pbi.item.satuan"]);

        $query->where("qty", ">", 0);
        $query->where("gudang_id", $gudang_id);

        $query->whereHas('pbi.item', function ($q) use ($barang_id) {
            $q->where("barang_id", $barang_id)
                ->where("tanggal_exp", ">=", Carbon::now()->format("Y-m-d"));
        });

        $items = $query->get();
        $items = $items->sortByDesc('qty');

        return response()->json([
            'items' => $items
        ]);
    }

    public function get_obat(int $gudang_id)
    {
        $query = WarehouseBarangFarmasi::with(["stored_items", "satuan", "zat_aktif", "zat_aktif.zat"]);
        $query->whereHas('stored_items', function ($q) use ($gudang_id) {
            $q->where('gudang_id', $gudang_id);
            $q->where('warehouse_penerimaan_barang_farmasi_item.qty', '>', 0);
        });

        $items = $query->get();
        foreach ($items as $item) {
            $stored = $item->stored_items->where('gudang_id', $gudang_id);
            $item->qty = $stored->sum('qty');
        }

        return response()->json([
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $default_apotek = WarehouseMasterGudang::select('id')->where('rajal_default', 1)->first();
        $obats = null;
        if (isset($default_apotek)) {
            $query = WarehouseBarangFarmasi::query()->with(["stored_items", "satuan", "zat_aktif", "zat_aktif.zat"]);
            $query->whereHas("stored_items", function ($q) use ($default_apotek) {
                $q->where("gudang_id", $default_apotek->id);
            });

            $obats = $query->get();
        }

        $signas = FarmasiSigna::all()->sortByDesc(function ($signa) {
            return strlen($signa->kata);
        });

        return view("pages.simrs.farmasi.transaksi-resep.resep", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get(),
            'default_apotek' => $default_apotek,
            'obats' => $obats,
            "signas" => $signas
        ]);
    }

    public function gudang_default_ranap()
    {
        $default_apotek = WarehouseMasterGudang::where("apotek", 1)->where("ranap_default", 1)->first();
        return $default_apotek ? $default_apotek->id : -1;
    }

    public function gudang_default_rajal()
    {
        $default_apotek = WarehouseMasterGudang::where("apotek", 1)->where("rajal_default", 1)->first();
        return $default_apotek ? $default_apotek->id : -1;
    }

    public function popupPilihPasien(Request $request, $poli)
    {
        $query = Registration::query()->with(['patient', 'departement', 'penjamin', 'doctor', 'doctor.employee']);
        $filters = ['registration_number'];
        $filterApplied = false;

        // active only
        $query->where('status', 'aktif');

        if ($poli == 'rajal') {
            $query->where('registration_type', 'rawat-jalan');
        } elseif ($poli == 'ranap') {
            $query->where('registration_type', 'rawat-inap');
        }
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registrations = $query->orderBy('registration_date', 'asc')->get();
        } else {
            // Return empty collection if no filters applied
            $registrations = collect();
        }

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-pasien', compact("registrations", "poli"));
    }

    public function popupPilihDokter()
    {
        $dokters = Doctor::with(["employee"])->get();

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-pilih-dokter', compact("dokters"));
    }

    public function popupResepElektronik(Request $request)
    {
        $relations = [
            "cppt",
            "items",
            "items.barang",
            "items.barang.satuan",
            "items.barang.stored_items",
            "registration",
            "registration.patient",
            "registration.doctor",
            "registration.penjamin",
            "registration.departement",
            "registration.doctor.employee"
        ];
        $query = FarmasiResepElektronik::query()->with($relations);
        $query->where("processed", 0);
        $filterApplied = false;

        // Filter by date range
        if ($request->filled('tanggal')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('registration_number')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $res = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return today's data if no filter is applied
            $res = FarmasiResepElektronik::with([$relations])->whereDate('created_at', Carbon::today())->orderBy('created_at', 'asc')->get();
        }


        foreach ($res as $re) {
            $gudang_id = $re->gudang_id;
            if (!isset($gudang_id)) {
                // recipe is manual only
                // ensure $re-items is null before continuing
                $re->items = null; // set to null to avoid errors in the view

                // continue
                continue;
            }

            foreach ($re->items as $item) {
                $stored = $item->barang->stored_items->where('gudang_id', $gudang_id);
                $item->barang->qty = $stored->sum('qty');
            }
        }


        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-resep-elektronik', compact('res'));
    }

    function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "OTC" . $year . $month . $day . $count;
    }

    public function generate_kode_resep($tipe_pasien)
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = FarmasiResep::whereDate('created_at', $date->toDateString())->where("tipe_pasien", $tipe_pasien)->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        $kode = "FRJ";
        if ($tipe_pasien == "ranap") {
            $kode = "FRI";
        } else if ($tipe_pasien == "otc") {
            $kode = "FRO";
        }

        return $kode . $year . $month . $day . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            'order_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            // 'kode_resep' => 'required|unique:farmasi_reseps,kode_resep',
            'embalase' => 'required|in:tidak,item,racikan',
            "tipe_pasien" => 'required|in:rajal,ranap,otc',
            "nama_pasien" => "required|string",
            'registration_id' => 'nullable|exists:registrations,id',
            // 'otc_id' => 'nullable|exists:registration_otc,id',
            're_id' => 'nullable|exists:farmasi_resep_elektroniks,id',
            'dokter_id' => 'nullable|exists:employees,id',
            'alamat' => 'nullable|string',
            'resep_manual' => 'nullable|string',
            'no_telp' => 'nullable',
            'total' => 'nullable|integer',
            'bmhp' => 'boolean',
            'kronis' => 'boolean',
            'dispensing' => 'boolean',
        ]);

        $data2 = $request->validate([
            "si_id.*" => "exists:stored_barang_farmasi,id",
            "obat_id.*" => "exists:warehouse_barang_farmasi,id",
            "type.*" => "in:obat,racikan",
            "signa.*" => "nullable|string",
            "jam_pemberian.*" => "nullable|string",
            "instruksi.*" => "nullable|string",
            "detail_racikan.*" => "integer",
            "subtotal.*" => "integer",
            "harga_embalase.*" => "integer",
            "hna.*" => "integer",
            "qty.*" => "integer",
        ]);

        $telaah_resep = null;
        if (!$request->has("telaah_resep") && $data["tipe_pasien"] != 'otc') {
            back()->with('error', "Telaah resep diperlukan untuk pasien non OTC.");
        } else if ($request->has("telaah_resep")) {
            $telaah_resep = json_decode($request->get("telaah_resep"), true);
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($data["user_id"]);

            if ($request->has("registration_id")) {
                $registration = Registration::findOrFail($request->get("registration_id"));
            } else {
                // OTC
                $registration = RegistrationOTC::create([
                    "nama_pasien" => $data["nama_pasien"],
                    "order_date" => $data["order_date"],
                    "registration_number" => $this->generate_otc_registration_number()
                ]);
                $data["otc_id"] = $registration->id;
            }

            $data["kode_resep"] = $this->generate_kode_resep($data["tipe_pasien"]);

            $resep = FarmasiResep::create($data);

            if ($data["tipe_pasien"] != 'otc') {
                $telaah_resep["resep_id"] = $resep->id;
                FarmasiTelaahResep::create($telaah_resep);
            }

            if ($request->has("re_id")) {
                $response = FarmasiResepResponse::where("re_id", $request->get("re_id"))->first();
                // dd($response);
                if (!$response) {
                    throw new \Exception("Respon resep elektronik tidak ditemukan");
                }

                $response->update([
                    "input_resep_user_id" => auth()->user()->id,
                    "input_resep_time" => Carbon::now(),
                ]);
                $response->save();

                $re = FarmasiResepElektronik::findOrFail($request->get("re_id"));
                $re->update([
                    "processed" => 1
                ]);
                $re->save();
            }

            // sort $data2["type"] so that the top ones are "racikan" first
            uasort($data2["type"], function ($a, $b) {
                return ($a === "racikan" ? 0 : 1) <=> ($b === "racikan" ? 0 : 1);
            });

            $resep_dict = [];

            foreach ($data2["type"] as $key => $type) {
                // ensure item exists and has required qty

                if (isset($data2["si_id"][$key])) {
                    $stored = StoredBarangFarmasi::find($data2["si_id"][$key]);
                    if ($stored->qty < $data2["qty"][$key]) {
                        throw new \Exception("Stok tidak cukup untuk item dengan ID " . $data2["si_id"][$key]);
                    }

                    // decrease current stock
                    $args = new IncreaseDecreaseStockArguments($user, $resep, $stored, $data2["qty"][$key]);
                    $this->goodsStockService->decreaseStock($args);
                }

                $detail_racikan_id = null;
                if (isset($data2["detail_racikan"][$key])) {
                    $detail_racikan_id = $resep_dict[$data2["detail_racikan"][$key]];
                }

                $item = FarmasiResepItems::create([
                    "resep_id"      => $resep->id,
                    "tipe"          => $type,
                    "si_id"         => isset($data2["si_id"][$key])            ? $data2["si_id"][$key]            : null,
                    "racikan_id"    => $detail_racikan_id,
                    "signa"         => isset($data2["signa"][$key])            ? $data2["signa"][$key]            : null,
                    "instruksi"     => isset($data2["instruksi"][$key])        ? $data2["instruksi"][$key]        : null,
                    "jam_pemberian" => isset($data2["jam_pemberian"][$key])    ? $data2["jam_pemberian"][$key]    : null,
                    "qty"           => isset($data2["qty"][$key])              ? $data2["qty"][$key]              : null,
                    "harga"         => isset($data2["hna"][$key])              ? $data2["hna"][$key]              : null,
                    "embalase"      => isset($data2["harga_embalase"][$key])   ? $data2["harga_embalase"][$key]   : null,
                    "subtotal"      => isset($data2["subtotal"][$key])         ? $data2["subtotal"][$key]         : null,
                ]);

                $resep_dict[$key] = $item->id;
            }

            DB::commit();
            return back()->with('success', 'Resep berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function telaahResepRaw(string $json)
    {
        $items = json_decode(base64_decode($json), true);

        return view('pages.simrs.farmasi.transaksi-resep.partials.popup-telaah-resep', compact('items'));
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiResep $farmasiResep)
    {
        //
    }
}
