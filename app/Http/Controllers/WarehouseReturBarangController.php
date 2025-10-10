<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
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
        // Query dasar dengan eager loading relasi utama
        $query = WarehouseReturBarang::with(['supplier', 'user']);

        // --- Blok Pencarian ---
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('kode_retur')) {
            $query->where('kode_retur', 'like', '%' . $request->kode_retur . '%');
        }
        if ($request->filled('tanggal_retur')) {
            // Menggunakan 'to' sebagai pemisah standar dari daterangepicker
            $dateRange = explode(' to ', $request->tanggal_retur);
            if (count($dateRange) === 2) {
                $startDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->endOfDay();
                $query->whereBetween('tanggal_retur', [$startDate, $endDate]);
            }
        }
        if ($request->filled('nama_barang')) {
            // Pencarian berdasarkan relasi yang lebih dalam
            $query->where(function ($q) use ($request) {
                $q->whereHas('items.storedFarmasi.pbi', function ($subq) use ($request) {
                    $subq->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
                })->orWhereHas('items.storedNonFarmasi.pbi', function ($subq) use ($request) {
                    $subq->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
                });
            });
        }
        // --- Akhir Blok Pencarian ---

        $rbs = $query->orderBy('created_at', 'desc')->get();

        return view('pages.simrs.warehouse.retur-barang.index', [
            'rbs' => $rbs,
            'suppliers' => WarehouseSupplier::all(),
        ]);
    }

    /**
     * Menyediakan data detail item untuk DataTables child row.
     * @param int $id ID dari WarehouseReturBarang
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($id)
    {
        $items = WarehouseReturBarangItems::with([
            // Eager load semua relasi yang dibutuhkan untuk detail
            'storedFarmasi.pbi.pb.po',
            'storedFarmasi.pbi.satuan',
            'storedNonFarmasi.pbi.pb.po',
            'storedNonFarmasi.pbi.satuan',
        ])
            ->where('rb_id', $id)
            ->get();

        // Menggunakan accessor 'stored' untuk menyederhanakan pengambilan data
        $formattedItems = $items->map(function ($item) {
            $stored = $item->stored;
            if (!$stored || !$stored->pbi) {
                return null; // Handle jika ada data yang tidak konsisten
            }

            return [
                'kode_penerimaan' => $stored->pbi->pb->kode_penerimaan ?? '-',
                'kode_po' => $stored->pbi->pb->po->kode_po ?? 'N/A',
                'kode_barang' => $stored->pbi->kode_barang ?? '-',
                'nama_barang' => $stored->pbi->nama_barang ?? '-',
                'satuan' => $stored->pbi->unit_barang ?? '-',
                'tanggal_exp' => $stored->pbi->tanggal_exp ? Carbon::parse($stored->pbi->tanggal_exp)->format('d-m-Y') : '-',
                'batch_no' => $stored->pbi->batch_no ?? '-',
                'qty' => $item->qty,
                'harga' => $item->harga,
                'subtotal' => $item->subtotal,
            ];
        })->filter(); // Menghapus item null dari koleksi

        return response()->json(['data' => $formattedItems]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.simrs.warehouse.penerimaan-barang.partials.popup-add-rb', [
            'suppliers' => WarehouseSupplier::all(),
            'gudangs' => WarehouseMasterGudang::where('aktif', 1)->get()
        ]);
    }

    /**
     * Menampilkan halaman popup untuk memilih item retur dengan filter gudang.
     * URL: /warehouse/retur-barang/popup-items/{supplier_id}/{gudang_id}
     */
    public function popupItems(Request $request, $supplier_id, $gudang_id)
    {
        $query1 = StoredBarangFarmasi::query()->with(['pbi.pb.supplier', 'pbi.satuan', 'gudang']);
        $query2 = StoredBarangNonFarmasi::query()->with(['pbi.pb.supplier', 'pbi.satuan', 'gudang']);

        // Filter berdasarkan gudang_id yang dipilih
        $query1->where('gudang_id', $gudang_id);
        $query2->where('gudang_id', $gudang_id);

        // Filter qty > 0 dan supplier_id
        $query1->where('qty', '>', 0)->whereHas('pbi.pb', function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });
        $query2->where('qty', '>', 0)->whereHas('pbi.pb', function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });

        $sbf = $query1->get();
        $sbnf = $query2->get();

        $sbs = collect(array_merge($sbf->all(), $sbnf->all()));
        $supplier = WarehouseSupplier::find($supplier_id);
        $gudang = \App\Models\WarehouseMasterGudang::find($gudang_id);

        return view('pages.simrs.warehouse.penerimaan-barang.partials.popup-items', [
            'sbs' => $sbs,
            'supplier' => $supplier,
            'gudang' => $gudang
        ]);
    }

    public function get_items(Request $request, $supplier_id)
    {
        $query1 = StoredBarangFarmasi::query()->with(['pbi.pb', 'pbi.satuan']);
        $query2 = StoredBarangNonFarmasi::query()->with(['pbi.pb', 'pbi.satuan']);

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

        return 'NRS' . $year . '-' . $count;
    }

    public function store(Request $request)
    {
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'tanggal_retur' => 'required|date',
            'keterangan' => 'nullable|string',
            'ppn' => 'required|integer',
            'ppn_nominal' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        $validatedData2 = $request->validate([
            'item_type.*' => 'required|in:si_f_id,si_nf_id',
            'item_si_id.*' => 'required|integer',
            'item_harga.*' => 'required|integer',
            'item_subtotal.*' => 'required|integer',
            'item_qty.*' => 'required|integer|min:1',
        ]);

        $validatedData1['kode_retur'] = $this->generate_rb_code();
        DB::beginTransaction();

        try {
            $rb = WarehouseReturBarang::create($validatedData1);
            $user = User::findOrFail($validatedData1['user_id']);

            foreach ($validatedData2['item_si_id'] as $key => $id) {
                if ($validatedData2['item_qty'][$key] == 0) continue;

                $si_item = ($validatedData2['item_type'][$key] == 'si_nf_id')
                    ? StoredBarangNonFarmasi::findOrFail($id)
                    : StoredBarangFarmasi::findOrFail($id);

                if ($si_item->qty < $validatedData2['item_qty'][$key]) {
                    throw new \Exception('Kuantitas stok tidak mencukupi untuk retur.');
                }

                WarehouseReturBarangItems::create([
                    'rb_id' => $rb->id,
                    'qty' => $validatedData2['item_qty'][$key],
                    'harga' => $validatedData2['item_harga'][$key],
                    'subtotal' => $validatedData2['item_subtotal'][$key],
                    $validatedData2['item_type'][$key] => $id,
                ]);

                $keteranganRetur = "Retur barang dengan kode: {$rb->kode_retur}";
                $args = new IncreaseDecreaseStockArguments($user, $rb, $si_item, $validatedData2['item_qty'][$key], $keteranganRetur);
                $this->goodsStockService->decreaseStock($args);
            }

            DB::commit();
            return redirect()->route('warehouse.penerimaan-barang.retur-barang')->with('success', 'Data retur berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function print($id)
    {
        return view('pages.simrs.warehouse.penerimaan-barang.partials.rb-print', [
            'rb' => WarehouseReturBarang::findorfail($id),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $rb = WarehouseReturBarang::findOrFail($id);

        DB::beginTransaction();
        try {
            $user = $request->user();
            foreach ($rb->items as $rbi) {
                $keteranganBatal = "Pembatalan retur barang dengan kode: {$rb->kode_retur}";
                $args = new IncreaseDecreaseStockArguments($user, $rb, $rbi->stored, $rbi->qty, $keteranganBatal);
                $this->goodsStockService->increaseStock($args);
            }
            $rb->items()->delete();
            $rb->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data retur berhasil dibatalkan dan stok dikembalikan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan retur: ' . $e->getMessage(),
            ]);
        }
    }
}
