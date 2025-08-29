<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseReturBarang;
use App\Models\WarehouseReturBarangItems;
use App\Models\WarehouseSupplier;
use App\Services\GoodsStockService;
use App\Services\IncreaseDecreaseStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseReturBarangController extends Controller
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
        $query = WarehouseReturBarang::query()->with(['items', 'items.stored', 'items.stored.pbi']);
        $filters = ['supplier_id', 'kode_retur'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%'.$request->$filter.'%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_retur')) {
            $dateRange = explode(' - ', $request->tanggal_retur);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_retur', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items.stored.pbi', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%'.$request->nama_barang.'%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $rb = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $rb = WarehouseReturBarang::all();
        }

        return view('pages.simrs.warehouse.retur-barang.index', [
            'rbs' => $rb,
            'suppliers' => WarehouseSupplier::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.simrs.warehouse.penerimaan-barang.partials.popup-add-rb', [
            'suppliers' => WarehouseSupplier::all(),
        ]);
    }

    public function get_items(Request $request, $supplier_id)
    {
        $query1 = StoredBarangFarmasi::query()->with(['pbi', 'pbi.pb']);
        $query2 = StoredBarangNonFarmasi::query()->with(['pbi', 'pbi.pb']);

        $query1->where('qty', '>', 0);
        $query2->where('qty', '>', 0);

        $query1->whereHas('pbi.pb', function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });

        $query2->whereHas('pbi.pb', function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });

        $sbf = $query1->get()->all();
        $sbnf = $query2->get()->all();

        // concat the array
        $sbs = collect(array_merge($sbf, $sbnf));

        return view('pages.simrs.warehouse.penerimaan-barang.partials.table-items-rb', [
            'sbs' => $sbs,
        ]);
    }

    private function generate_rb_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');

        $count = WarehouseReturBarang::withTrashed()
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return 'NRS'.$year.'-'.$count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'tanggal_retur' => 'required|date',
            'keterangan' => 'nullable|string',
            'ppn' => 'required|integer',
            'ppn_nominal' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        // dd($validatedData1);

        $validatedData2 = $request->validate([
            'item_type.*' => 'required|in:si_f_id,si_nf_id',
            'item_si_id.*' => 'required|integer',
            'item_harga.*' => 'required|integer',
            'item_subtotal.*' => 'required|integer',
            'item_qty.*' => 'required|integer|min:0',
        ]);

        // dd($validatedData2);

        $validatedData1['kode_retur'] = $this->generate_rb_code();
        DB::beginTransaction();

        try {
            $rb = WarehouseReturBarang::create($validatedData1);
            $user = User::findOrFail($validatedData1['user_id']);

            foreach ($validatedData2['item_si_id'] as $key => $id) {
                if ($validatedData2['item_qty'][$key] == 0) {
                    continue; // ignore if qty is 0
                }

                $si = StoredBarangFarmasi::query();
                if ($validatedData2['item_type'][$key] == 'si_nf_id') {
                    $si = StoredBarangNonFarmasi::query();
                }

                $si_item = $si->findOrFail($id);
                if ($si_item->qty < $validatedData2['item_qty'][$key]) {
                    // throw error
                    throw new \Exception('Qty tidak cukup'); // throw error
                }

                WarehouseReturBarangItems::create([
                    'rb_id' => $rb->id,
                    'qty' => $validatedData2['item_qty'][$key],
                    'harga' => $validatedData2['item_harga'][$key],
                    'subtotal' => $validatedData2['item_subtotal'][$key],
                    $validatedData2['item_type'][$key] => $id,
                ]);

                // $si_item->update([
                // "qty" => $si_item->qty - $validatedData2["item_qty"][$key]
                // ]);
                // $si_item->save();

                // ...

                // use the GoodsStockService
                $qty = $validatedData2['item_qty'][$key];
                $args = new IncreaseDecreaseStockArguments($user, $rb, $si_item, $qty);
                $this->goodsStockService->decreaseStock($args);
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        return view('pages.simrs.warehouse.penerimaan-barang.partials.rb-print', [
            'rb' => WarehouseReturBarang::findorfail($id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseReturBarang $warehouseReturBarang, $id)
    {
        $rb = $warehouseReturBarang->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($rb->items as $rbi) {
                // $rbi->stored->update([
                //     'qty' => $rbi->stored->qty + $rbi->qty,
                // ]);
                // $rbi->stored->save();

                // use the GoodsStockService
                $user = request()->user();
                $args = new IncreaseDecreaseStockArguments($user, $rb, $rbi->stored, $rbi->qty);
                $this->goodsStockService->increaseStock($args);

                $rbi->delete();
            }
            $rb->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
