<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseDistribusiBarangFarmasi;
use App\Models\WarehouseDistribusiBarangFarmasiItems;
use App\Models\WarehousePenerimaanBarangFarmasiItems;
use App\Models\WarehouseStockRequestPharmacy;
use App\Models\WarehouseStockRequestPharmacyItems;
use App\Services\CreateStockArguments;
use App\Services\GoodsStockService;
use App\Services\GoodsType;
use App\Services\IncreaseDecreaseStockArguments;
use App\Services\MoveStockArguments;
use App\Services\TransferStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Throwable; // Import Throwable untuk exception handling yang lebih baik

class WarehouseDistribusiBarangFarmasiController extends Controller
{
    protected GoodsStockService $goodsStockService;

    public function __construct(GoodsStockService $goodsStockService)
    {
        $this->goodsStockService = $goodsStockService;
        $this->goodsStockService->controller = self::class;
    }

    /**
     * Menampilkan daftar distribusi barang dan menangani request AJAX untuk DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = WarehouseDistribusiBarangFarmasi::with(['asal', 'tujuan', 'user.employee', 'sr'])->select('warehouse_distribusi_barang_farmasi.*');

            // Server-side filtering
            if ($request->filled('kode_db')) {
                $query->where('kode_db', 'like', '%' . $request->kode_db . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('asal_gudang_id')) {
                $query->where('asal_gudang_id', $request->asal_gudang_id);
            }
            if ($request->filled('tujuan_gudang_id')) {
                $query->where('tujuan_gudang_id', $request->tujuan_gudang_id);
            }
            if ($request->filled('tanggal_db')) {
                $dateRange = explode(' - ', $request->tanggal_db);
                if (count($dateRange) === 2) {
                    $startDate = Carbon::parse($dateRange[0])->startOfDay();
                    $endDate = Carbon::parse($dateRange[1])->endOfDay();
                    $query->whereBetween('tanggal_db', [$startDate, $endDate]);
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" onclick="printData(' . $row->id . ')" class="btn btn-icon btn-primary btn-xs rounded-circle" title="Print"><i class="fal fa-print"></i></a> ';
                    if ($row->status == 'draft') {
                        $btn .= '<a href="javascript:void(0)" onclick="editData(' . $row->id . ')" class="btn btn-icon btn-warning btn-xs rounded-circle" title="Edit"><i class="fal fa-pencil"></i></a> ';
                        $btn .= '<a href="javascript:void(0)" onclick="deleteData(' . $row->id . ')" class="btn btn-icon btn-danger btn-xs rounded-circle" title="Hapus"><i class="fal fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->editColumn('tanggal_db', fn($data) => Carbon::parse($data->tanggal_db)->isoFormat('D MMMM YYYY'))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("pages.simrs.warehouse.distribusi-barang.pharmacy", [
            "gudangs" => WarehouseMasterGudang::where('aktif', 1)->get(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
        ]);
    }

    /**
     * Menampilkan detail item untuk child row DataTables.
     */
    public function getDetailItems($id)
    {
        $db = WarehouseDistribusiBarangFarmasi::with(['items.barang', 'items.satuan'])->findOrFail($id);
        return view("pages.simrs.warehouse.distribusi-barang.partials.db-detail-childrow", compact('db'));
    }

    /**
     * Menampilkan form untuk membuat distribusi barang baru.
     */
    public function create()
    {
        $srs = WarehouseStockRequestPharmacy::with(["items.barang", "items.satuan"])
            ->where("status", "final")
            ->whereHas("items", fn($q) => $q->whereColumn("qty_fulfilled", "<", "qty"))
            ->get();

        $gudangs = WarehouseMasterGudang::where('aktif', 1)->get();
        $gudang_asals = WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get();

        return view("pages.simrs.warehouse.distribusi-barang.partials.popup-add-db-farmasi", compact('srs', 'gudangs', 'gudang_asals'));
    }

    /**
     * Menampilkan form untuk mengedit distribusi barang.
     */
    public function edit($id)
    {
        $db = WarehouseDistribusiBarangFarmasi::with('items.barang', 'items.satuan')->findOrFail($id);

        // Menambahkan info stok saat ini ke setiap item untuk ditampilkan di form
        foreach ($db->items as $item) {
            $item->stock = StoredBarangFarmasi::where('gudang_id', $db->asal_gudang_id)
                ->whereHas('pbi', fn($q) => $q->where('barang_id', $item->barang_id)->where('satuan_id', $item->satuan_id))
                ->sum('qty');
        }

        $srs = WarehouseStockRequestPharmacy::with(["items.barang", "items.satuan"])
            ->where("status", "final")
            ->whereHas("items", fn($q) => $q->whereColumn("qty_fulfilled", "<", "qty"))
            ->get();

        return view("pages.simrs.warehouse.distribusi-barang.partials.popup-edit-db-farmasi", [
            "db" => $db,
            "srs" => $srs,
            "gudangs" => WarehouseMasterGudang::where('aktif', 1)->get(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
        ]);
    }

    /**
     * Menyimpan data distribusi barang baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "user_id" => "required|exists:users,id",
            "asal_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tujuan_gudang_id" => "required|exists:warehouse_master_gudang,id|different:asal_gudang_id",
            "tanggal_db" => "required|date",
            "sr_id" => "nullable|exists:warehouse_stock_request_pharmacy,id",
            "status" => "required|in:draft,final",
            "keterangan" => "nullable|string|max:255",
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:warehouse_barang_farmasi,id',
            'items.*.satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.sri_id' => 'nullable|exists:warehouse_stock_request_pharmacy_item,id',
        ]);

        DB::beginTransaction();
        try {
            $dbHeader = collect($validatedData)->except('items')->toArray();
            $dbHeader['kode_db'] = $this->generateDbCode();

            $db = WarehouseDistribusiBarangFarmasi::create($dbHeader);
            $user = User::findOrFail($validatedData["user_id"]);
            $items = [];

            foreach ($validatedData['items'] as $itemData) {
                $items[] = new WarehouseDistribusiBarangFarmasiItems($itemData);

                if ($db->status == "final") {
                    $this->processDistribution(
                        $user,
                        $db,
                        $db->asal_gudang_id,
                        $db->tujuan_gudang_id,
                        $itemData['barang_id'],
                        $itemData['satuan_id'],
                        $itemData['qty'],
                        $itemData['sri_id'] ?? null
                    );
                }
            }

            $db->items()->saveMany($items);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Distribusi Barang berhasil disimpan.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Memperbarui data distribusi barang.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "user_id" => "required|exists:users,id",
            "asal_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tujuan_gudang_id" => "required|exists:warehouse_master_gudang,id|different:asal_gudang_id",
            "tanggal_db" => "required|date",
            "sr_id" => "nullable|exists:warehouse_stock_request_pharmacy,id",
            "status" => "required|in:draft,final",
            "keterangan" => "nullable|string|max:255",
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'nullable|exists:warehouse_distribusi_barang_farmasi_item,id',
            'items.*.barang_id' => 'required|exists:warehouse_barang_farmasi,id',
            'items.*.satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.sri_id' => 'nullable|exists:warehouse_stock_request_pharmacy_item,id',
        ]);

        DB::beginTransaction();
        try {
            $db = WarehouseDistribusiBarangFarmasi::findOrFail($id);
            if ($db->status === 'final') {
                throw new \Exception("Dokumen yang sudah final tidak dapat diubah.");
            }

            $dbHeader = collect($validatedData)->except('items')->toArray();
            $db->update($dbHeader);
            $user = User::findOrFail($validatedData["user_id"]);

            $submittedItemIds = collect($validatedData['items'])->pluck('item_id')->filter()->toArray();
            $db->items()->whereNotIn('id', $submittedItemIds)->delete();

            foreach ($validatedData['items'] as $itemData) {
                $item = $db->items()->updateOrCreate(
                    ['id' => $itemData['item_id'] ?? null],
                    $itemData
                );

                if ($db->status == "final") {
                    $this->processDistribution(
                        $user,
                        $db,
                        $db->asal_gudang_id,
                        $db->tujuan_gudang_id,
                        $item->barang_id,
                        $item->satuan_id,
                        $item->qty,
                        $item->sri_id ?? null
                    );
                }
            }

            DB::commit();
            return view('pages.simrs.partials.success-popup', ['message' => 'Distribusi Barang berhasil diperbarui.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus data distribusi barang (hanya jika draft).
     */
    public function destroy($id)
    {
        try {
            $db = WarehouseDistribusiBarangFarmasi::findOrFail($id);
            if ($db->status == 'final') {
                return response()->json(['success' => false, 'message' => "Dokumen yang sudah final tidak bisa dihapus!"], 403);
            }
            $db->items()->delete();
            $db->delete();
            return response()->json(['success' => true, 'message' => 'Distribusi Barang berhasil dihapus!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mencetak dokumen distribusi barang.
     */
    public function print($id)
    {
        return view("pages.simrs.warehouse.distribusi-barang.partials.db-print-pharmacy", [
            "db" => WarehouseDistribusiBarangFarmasi::findOrFail($id)
        ]);
    }

    /**
     * [HELPER] Menghasilkan kode unik untuk distribusi barang.
     */
    private function generateDbCode()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $count = WarehouseDistribusiBarangFarmasi::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count() + 1;
        return sprintf('%06d/DBF/%s%s', $count, $year, $month);
    }

    /**
     * [HELPER] Mendapatkan informasi stok untuk item tertentu via AJAX.
     */
    public function get_stock($gudang_id, $barang_id, $satuan_id)
    {
        $totalStock = StoredBarangFarmasi::where("gudang_id", $gudang_id)
            ->whereHas("pbi", fn($q) => $q->where("barang_id", $barang_id)->where("satuan_id", $satuan_id))
            ->sum('qty');

        return response()->json(["qty" => $totalStock]);
    }

    /**
     * [HELPER] Logika utama untuk memproses perpindahan stok barang.
     */
    private function processDistribution(User $user, WarehouseDistribusiBarangFarmasi $db, int $asal_gudang_id, int $tujuan_gudang_id, int $barang_id, int $satuan_id, int $requested_qty, ?int $sri_id)
    {
        if ($sri_id) {
            $sri = WarehouseStockRequestPharmacyItems::findOrFail($sri_id);
            $sri->qty_fulfilled = ($sri->qty_fulfilled ?? 0) + $requested_qty;
            if ($sri->qty < $sri->qty_fulfilled) $sri->qty_fulfilled = $sri->qty;
            $sri->save();
        }

        $origin_sis = StoredBarangFarmasi::where("gudang_id", $asal_gudang_id)
            ->whereHas("pbi", fn($q) => $q->where("barang_id", $barang_id)->where("satuan_id", $satuan_id))
            ->where('qty', '>', 0)
            ->orderBy('created_at', 'asc') // FIFO
            ->get();

        $available_stock = $origin_sis->sum("qty");
        if ($available_stock < $requested_qty) {
            $barang = \App\Models\WarehouseBarangFarmasi::find($barang_id);
            throw new \Exception("Stok tidak cukup untuk barang '{$barang->nama_barang}'. Tersedia: {$available_stock}, Diminta: {$requested_qty}");
        }

        $transfered = 0;
        foreach ($origin_sis as $si) {
            if ($transfered >= $requested_qty) break;

            $qtyToProcess = min($si->qty, $requested_qty - $transfered);

            // Cek apakah di gudang tujuan sudah ada stok dari batch (PBI) yang sama
            $si_tujuan = StoredBarangFarmasi::where("gudang_id", $tujuan_gudang_id)->where("pbi_id", $si->pbi_id)->first();

            if ($si_tujuan) {
                // Opsi 1: Transfer (tambahkan stok ke yang sudah ada)
                $args = new TransferStockArguments($user, $db, $si, $si_tujuan, $qtyToProcess);
                $this->goodsStockService->transferStock($args);
            } elseif ($qtyToProcess == $si->qty) {
                // Opsi 2: Pindahkan seluruh batch jika qty-nya pas
                $warehouse = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);
                $args = new MoveStockArguments($user, $db, $si, $warehouse);
                $this->goodsStockService->moveStock($args);
            } else {
                // Opsi 3: Split (kurangi dari asal, buat baru di tujuan)
                $decrease_args = new IncreaseDecreaseStockArguments($user, $db, $si, $qtyToProcess);
                $this->goodsStockService->decreaseStock($decrease_args);

                $warehouse = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);
                $keterangan_split = "Split stock for DB: {$db->kode_db}";
                $new_stock_args = new CreateStockArguments($user, $db, GoodsType::Pharmacy, $warehouse, $si->pbi, $keterangan_split, $qtyToProcess);
                $this->goodsStockService->createStock($new_stock_args);
            }
            $transfered += $qtyToProcess;
        }
    }

    /**
     * Mengambil data item untuk modal pemilihan barang.
     * Dipanggil melalui AJAX.
     */
    public function getItemGudangForModal($asal_gudang_id, $tujuan_gudang_id)
    {
        $gudang_asal = WarehouseMasterGudang::findOrFail($asal_gudang_id);
        $gudang_tujuan = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);

        // Query yang lebih kokoh dengan eager loading dan pengecekan relasi
        $sis_asal = StoredBarangFarmasi::with([
            // Muat relasi pbi, lalu dari pbi muat relasi barang dan satuan
            'pbi' => function ($query) {
                $query->with(['barang', 'satuan']);
            }
        ])
            // Hanya ambil data yang punya relasi pbi yang valid
            ->whereHas('pbi.barang')
            // Filter berdasarkan gudang asal
            ->where('gudang_id', $asal_gudang_id)
            // Filter hanya yang stoknya lebih dari 0
            ->where('qty', '>', 0)
            ->get();

        // Opsional: Jika ingin mengelompokkan berdasarkan barang dan satuan,
        // karena satu barang bisa ada di beberapa batch (PBI) yang berbeda.
        $items_grouped = $sis_asal->groupBy(function ($item) {
            // Buat kunci unik berdasarkan ID barang dan ID satuan
            return $item->pbi->barang_id . '-' . $item->pbi->satuan_id;
        })->map(function ($group) {
            // Ambil item pertama sebagai representasi (karena barang & satuannya sama)
            $first_item = $group->first();
            // Jumlahkan total qty dari semua batch untuk barang & satuan ini
            $total_qty = $group->sum('qty');

            return (object) [
                'barang' => $first_item->pbi->barang,
                'satuan' => $first_item->pbi->satuan,
                'total_qty' => $total_qty,
            ];
        });

        return view("pages.simrs.warehouse.distribusi-barang.partials.modal-add-item-content", [
            // Kirim data yang sudah dikelompokkan ke view
            "items" => $items_grouped,
            "gudang_asal" => $gudang_asal,
            "gudang_tujuan" => $gudang_tujuan,
        ]);
    }
}
