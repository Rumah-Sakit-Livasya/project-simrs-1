<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderPharmacy;
use App\Models\ProcurementPurchaseOrderPharmacyItems;
use App\Models\StoredBarangFarmasi;
use App\Models\User;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehousePenerimaanBarangFarmasi;
use App\Models\WarehousePenerimaanBarangFarmasiItems;
use App\Models\WarehouseSupplier;
use App\Services\CreateStockArguments;
use App\Services\GoodsStockService;
use App\Services\GoodsType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePenerimaanBarangFarmasiController extends Controller
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
        $query = WarehousePenerimaanBarangFarmasi::query()->with(['items', 'po']);
        $filters = ['kode_penerimaan', 'no_faktur'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_terima')) {
            $dateRange = explode(' - ', $request->tanggal_terima);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('batch_no')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('batch_no', 'like', '%' . $request->batch_no . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('kode_po')) {
            $query->whereHas('po', function ($q) use ($request) {
                $q->where('kode_po', 'like', '%' . $request->kode_po . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $pb = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $pb = WarehousePenerimaanBarangFarmasi::all();
        }

        return view('pages.simrs.warehouse.penerimaan-barang.pharmacy', [
            'pbs' => $pb,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $query_pos = ProcurementPurchaseOrderPharmacy::query()->with('items', 'items.barang');
        $query_pos->whereHas('items', function ($q) {
            $q
                ->whereColumn('qty_received', '<', 'qty')
                ->orWhereNull('qty_received');
        });
        $pos = $query_pos->get();

        return view('pages.simrs.warehouse.penerimaan-barang.partials.popup-add-pb-farmasi', [
            'gudangs' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
            'suppliers' => WarehouseSupplier::all(),
            'pos' => $pos,
            'barangs' => WarehouseBarangFarmasi::with(['satuan'])->get(),
        ]);
    }

    private function generate_pb_code($auto)
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehousePenerimaanBarangFarmasi::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        if ($auto == true) {
            $code = 'FNGR';
        } else {
            $code = 'FRGR';
        }

        return $count . '/' . $code . '/' . $year . $month;
    }

    private function generate_auto_po_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = ProcurementPurchaseOrderPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . '/FNPO/' . $year . $month;
    }

    private function createAutoPO(Request $request, $kode)
    {
        $validatedData1 = [
            'tanggal_po' => Carbon::now()->toDateString(),
            'tanggal_kirim' => Carbon::now()->toDateString(),
            'pic_terima' => $request->get('pic_penerima'),
            'tipe_top' => 'SETELAH_TERIMA_BARANG',
            'top' => 'COD',
            'user_id' => $request->get('user_id'),
            'ppn' => $request->get('ppn'),
            'supplier_id' => $request->get('supplier_id'),
            'tipe' => 'normal',
            'nominal' => $request->get('total_final'),
            'status' => 'final',
            'keterangan' => 'Auto-Generated Purchase Order from Penerimaan Barang ' . $kode . '. ' . $request->get('keterangan'),
            'is_auto' => 1,
        ];

        // validated data form Penerimaan Barang
        // double check to make sure it match Purchase Order columns
        $validatedData2 = $request->validate([
            'kode_barang.*' => 'required|string|max:255',
            'nama_barang.*' => 'required|string|max:255',
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'unit_barang.*' => 'nullable|string|max:100',  // Assuming unit could be nullable
            'tanggal_exp.*' => 'required|date',
            'batch_no.*' => 'required|string|max:255',     // Batch numbers often need to be verified for proper format and length
            'qty.*' => 'required|integer|min:0',
            'harga.*' => 'required|numeric|min:0',
            'subtotal.*' => 'required|numeric|min:0',
            'diskon_nominal.*' => 'required|numeric|min:0',
            'is_bonus.*' => 'nullable|boolean',
            'poi_id.*' => 'nullable|exists:procurement_purchase_order_pharmacy_items,id',
        ]);

        $validatedData1['kode_po'] = $this->generate_auto_po_code();

        DB::beginTransaction();
        try {
            $po = ProcurementPurchaseOrderPharmacy::create($validatedData1);

            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                ProcurementPurchaseOrderPharmacyItems::create([
                    'po_id' => $po->id,
                    'pri_id' => null,
                    'barang_id' => $validatedData2['barang_id'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'harga_barang' => $validatedData2['harga'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'qty_received' => $validatedData2['qty'][$key],
                    'qty_bonus' => 0,
                    'discount_nominal' => $validatedData2['diskon_nominal'][$key],
                    'subtotal' => $validatedData2['subtotal'][$key],
                ]);
            }

            DB::commit();

            return $po;
        } catch (\Exception $e) {
            DB::rollBack();
            // throw exception
            throw $e; // Uncomment this line to throw the exception and see the error message in the console or logs. This is useful for debugging purposes.

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'po_id' => 'nullable|exists:procurement_purchase_order_pharmacy,id',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'status' => 'required|in:draft,final',
            'tipe_bayar' => 'required|in:cash,non_cash',
            'tipe_terima' => 'required|in:po,npo',
            'no_faktur' => 'required|string',
            'pic_penerima' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tanggal_terima' => 'required|date',
            'tanggal_faktur' => 'nullable|date',
            'ppn' => 'required|integer',
            'ppn_nominal' => 'required|integer',
            'materai' => 'nullable|integer',
            'total' => 'required|integer',
            'total_final' => 'required|integer',

            // temporary in string
            'kas' => 'nullable|string',
        ]);

        // dd($validatedData1);

        // Validate the incoming request data against our rules.
        $validatedData2 = $request->validate([
            'kode_barang.*' => 'required|string|max:255',
            'nama_barang.*' => 'required|string|max:255',
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'unit_barang.*' => 'nullable|string|max:100',  // Assuming unit could be nullable
            'tanggal_exp.*' => 'required|date',
            'batch_no.*' => 'required|string|max:255',     // Batch numbers often need to be verified for proper format and length
            'qty.*' => 'required|integer|min:0',
            'harga.*' => 'required|numeric|min:0',
            'subtotal.*' => 'required|numeric|min:0',
            'diskon_nominal.*' => 'required|numeric|min:0',
            'is_bonus.*' => 'nullable|boolean',
            'poi_id.*' => 'nullable|exists:procurement_purchase_order_pharmacy_items,id',
        ]);

        // dd($validatedData2);

        $auto = isset($validatedData1['po_id']) ? false : true;
        $validatedData1['kode_penerimaan'] = $this->generate_pb_code($auto);

        DB::beginTransaction();
        try {
            $pb = WarehousePenerimaanBarangFarmasi::create($validatedData1);

            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                if ($validatedData2['qty'][$key] == 0) {
                    continue;
                }

                if (! $auto) { // based on PO
                    $poi = ProcurementPurchaseOrderPharmacyItems::findOrFail($validatedData2['poi_id'][$key]);
                    // check if $poi->qty_received + $validatedData2["qty"][$key] doesn't exceed $poi->qty
                    if ($poi->qty_received + $validatedData2['qty'][$key] > $poi->qty) {
                        throw new \Exception('Qty received exceeds PO qty for item ' . $poi->barang->nama . '(' . $poi->id . ')');
                    }
                }

                $is_bonus = isset($validatedData2['is_bonus']) && isset($validatedData2['is_bonus'][$key])
                    ? $validatedData2['is_bonus'][$key]
                    : false;

                $poi_id = $auto ? null : $validatedData2['poi_id'][$key];

                $pbi = WarehousePenerimaanBarangFarmasiItems::create([
                    'pb_id' => $pb->id,
                    'poi_id' => $poi_id,
                    'barang_id' => $barang_id,
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'batch_no' => $validatedData2['batch_no'][$key],
                    'tanggal_exp' => $validatedData2['tanggal_exp'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'harga' => $validatedData2['harga'][$key],
                    'diskon_nominal' => $validatedData2['diskon_nominal'][$key],
                    'subtotal' => $validatedData2['subtotal'][$key],
                    'is_bonus' => $is_bonus,
                ]);

                if ($validatedData1['status'] == 'final') {
                    if (! $auto) {
                        $poi->update([
                            'qty_received' => $poi->qty_received + $validatedData2['qty'][$key], // update POI received quantity
                        ]);
                        $poi->save();
                    }

                    // StoredBarangFarmasi::create([
                    //     "pbi_id" => $pbi->id,
                    //     "gudang_id" => $validatedData1["gudang_id"],
                    //     "qty" => $validatedData2["qty"][$key]
                    // ]);

                    $user = User::findOrFail($validatedData1['user_id']);
                    $source = $pb;
                    $type = GoodsType::Pharmacy;
                    $warehouse = WarehouseMasterGudang::findOrFail($validatedData1['gudang_id']);
                    $qty = $validatedData2['qty'][$key];
                    // Tambahkan argumen keterangan (misalnya string kosong) sebelum $qty
                    $keterangan = "Penerimaan barang dari faktur no: {$pb->no_faktur}";
                    $args = new CreateStockArguments($user, $source, $type, $warehouse, $pbi, $keterangan, $qty); // <-- INI YANG BENAR
                    $this->goodsStockService->createStock($args);
                }
            }

            if ($validatedData1['status'] == 'final') {
                if ($auto) {
                    // generate auto po
                    $po = $this->createAutoPO($request, $validatedData1['kode_penerimaan']);
                    $pb->update([
                        'po_id' => $po->id,
                    ]);
                    $pb->save();
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehousePenerimaanBarangFarmasi $warehousePenerimaanBarangFarmasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehousePenerimaanBarangFarmasi $warehousePenerimaanBarangFarmasi, $id)
    {
        return view('pages.simrs.warehouse.penerimaan-barang.partials.popup-edit-pb-farmasi', [
            'gudangs' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
            'suppliers' => WarehouseSupplier::all(),
            'pb' => $warehousePenerimaanBarangFarmasi::findorfail($id),
            'barangs' => WarehouseBarangFarmasi::with(['satuan'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehousePenerimaanBarangFarmasi $warehousePenerimaanBarangFarmasi, $id)
    {
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'po_id' => 'nullable|exists:procurement_purchase_order_pharmacy,id',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'status' => 'required|in:draft,final',
            'tipe_bayar' => 'required|in:cash,non_cash',
            'no_faktur' => 'required|string',
            'pic_penerima' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tanggal_terima' => 'required|date',
            'tanggal_faktur' => 'nullable|date',
            'ppn' => 'required|integer',
            'ppn_nominal' => 'required|integer',
            'materai' => 'nullable|integer',
            'total' => 'required|integer',
            'total_final' => 'required|integer',

            // temporary in string
            'kas' => 'nullable|string',
        ]);

        // dd($validatedData1);

        // Validate the incoming request data against our rules.
        $validatedData2 = $request->validate([
            'kode_barang.*' => 'required|string|max:255',
            'nama_barang.*' => 'required|string|max:255',
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'unit_barang.*' => 'nullable|string|max:100',  // Assuming unit could be nullable
            'tanggal_exp.*' => 'required|date',
            'batch_no.*' => 'required|string|max:255',     // Batch numbers often need to be verified for proper format and length
            'qty.*' => 'required|integer|min:0',
            'harga.*' => 'required|numeric|min:0',
            'subtotal.*' => 'required|numeric|min:0',
            'diskon_nominal.*' => 'required|numeric|min:0',
            'is_bonus.*' => 'nullable|boolean',
            'poi_id.*' => 'nullable|exists:procurement_purchase_order_pharmacy_items,id',
            'item_id' => 'nullable|array',
            'item_id.*' => 'integer',
        ]);
        $auto = isset($validatedData1['po_id']) ? false : true;

        DB::beginTransaction();
        try {
            $pb = WarehousePenerimaanBarangFarmasi::findOrFail($id);
            $pb->update($validatedData1);

            if (count($validatedData2['item_id']) > 0) {
                WarehousePenerimaanBarangFarmasiItems::where('pb_id', $pb->id)
                    ->whereNotIn('id', $validatedData2['item_id'])
                    ->delete(); // don't force delete to retain history
            }

            foreach ($validatedData2['barang_id'] as $key => $item_id) {
                if (! $auto) { // based on PO
                    $poi = ProcurementPurchaseOrderPharmacyItems::findOrFail($validatedData2['poi_id'][$key]);
                    // check if $poi->qty_received + $validatedData2["qty"][$key] doesn't exceed $poi->qty
                    if ($poi->qty_received + $validatedData2['qty'][$key] > $poi->qty) {
                        throw new \Exception('Qty received exceeds PO qty for item ' . $poi->barang->nama . '(' . $poi->id . ')');
                    }
                }

                $is_bonus = isset($validatedData2['is_bonus']) && isset($validatedData2['is_bonus'][$key])
                    ? $validatedData2['is_bonus'][$key]
                    : false;

                $poi_id = $auto ? null : $validatedData2['poi_id'][$key];

                $attributes = [
                    'pb_id' => $pb->id,
                    'poi_id' => $poi_id,
                    'barang_id' => $validatedData2['barang_id'][$key],
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'batch_no' => $validatedData2['batch_no'][$key],
                    'tanggal_exp' => $validatedData2['tanggal_exp'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'harga' => $validatedData2['harga'][$key],
                    'diskon_nominal' => $validatedData2['diskon_nominal'][$key],
                    'subtotal' => $validatedData2['subtotal'][$key],
                    'is_bonus' => $is_bonus,
                ];

                if ($request->has('item_id') && isset($validatedData2['item_id'][$key])) {
                    $pbi = WarehousePenerimaanBarangFarmasiItems::findOrFail($validatedData2['item_id'][$key]);
                    $pbi->update($attributes);
                } else {
                    $pbi = new WarehousePenerimaanBarangFarmasiItems($attributes);
                    $pbi->save(); // Make sure $pbi has an ID before passing to stock creation
                }

                // !!! Penting !!!
                // Simpan terlebih dahulu supaya $pbi->id tidak null.
                // Lalu createStock dengan $pbi yang sudah ada id-nya.

                if ($validatedData1['status'] === 'final') {
                    if (! $auto) {
                        $poi->update([
                            'qty_received' => $poi->qty_received + $validatedData2['qty'][$key], // update POI received quantity
                        ]);
                        $poi->save();
                    }

                    $user = User::findOrFail($validatedData1['user_id']);
                    $source = $pb;
                    $type = GoodsType::Pharmacy;
                    $warehouse = WarehouseMasterGudang::findOrFail($validatedData1['gudang_id']);
                    $qty = $validatedData2['qty'][$key];
                    $keterangan = "Update penerimaan barang dari faktur no: {$pb->no_faktur}";
                    $args = new CreateStockArguments($user, $source, $type, $warehouse, $pbi, $keterangan, $qty);
                    $this->goodsStockService->createStock($args);
                }
            }

            if ($validatedData1['status'] == 'final') {
                if ($auto) {
                    // generate auto po
                    $po = $this->createAutoPO($request, $pb->kode_penerimaan);
                    $pb->update([
                        'po_id' => $po->id,
                    ]);
                    $pb->save();
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehousePenerimaanBarangFarmasi $warehousePenerimaanBarangFarmasi, $id)
    {
        $pb = $warehousePenerimaanBarangFarmasi->findorfail($id);
        if ($pb->status == 'final') {
            return response()->json([
                'success' => false,
                'message' => 'Penerimaan Barang sudah final, tidak bisa dihapus!',
            ]);
        }

        try {
            $pb->delete();

            return response()->json([
                'success' => true,
                'message' => 'Penerimaan Barang berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function print($id)
    {
        return view('pages.simrs.warehouse.penerimaan-barang.partials.pb-print-pharmacy', [
            'pb' => WarehousePenerimaanBarangFarmasi::findorfail($id),
        ]);
    }

    /**
     * Menampilkan childrow untuk penerimaan barang farmasi.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($id)
    {
        // Ambil data items untuk penerimaan barang farmasi tertentu
        $items = WarehousePenerimaanBarangFarmasiItems::with(['item', 'satuan'])
            ->where('pb_id', $id)
            ->get();

        // Anda bisa menyesuaikan struktur data yang dikembalikan sesuai kebutuhan frontend
        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
