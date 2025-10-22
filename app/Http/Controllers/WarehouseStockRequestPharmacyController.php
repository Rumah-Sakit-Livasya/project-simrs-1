<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseStockRequestPharmacy;
use App\Models\WarehouseStockRequestPharmacyItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables; // Tambahkan ini

class WarehouseStockRequestPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
     {
         if ($request->ajax()) {
             $query = WarehouseStockRequestPharmacy::with(['asal', 'tujuan', 'user.employee'])->select('warehouse_stock_request_pharmacy.*');

             // Server-side filtering
             if ($request->filled('kode_sr')) {
                 $query->where('kode_sr', 'like', '%' . $request->kode_sr . '%');
             }
             if ($request->filled('status')) {
                 $query->where('status', $request->status);
             }
             if ($request->filled('tanggal_sr')) {
                 $dateRange = explode(' - ', $request->tanggal_sr);
                 if (count($dateRange) === 2) {
                     $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                     $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                     $query->whereBetween('tanggal_sr', [$startDate, $endDate]);
                 }
             }
             if ($request->filled('nama_barang')) {
                 $query->whereHas('items', function ($q) use ($request) {
                     $q->whereHas('barang', function ($subQ) use ($request) {
                         $subQ->where('nama', 'like', '%' . $request->nama_barang . '%');
                     });
                 });
             }

             return DataTables::of($query)
                 ->addIndexColumn() // DT_RowIndex
                 ->addColumn('action', function ($row) {
                     $buttons = [];
                     // Tombol print selalu ada
                     $buttons[] = '<button onclick="window.printData(' . $row->id . ')" class="btn btn-primary btn-icon btn-xs rounded-circle" title="Print"><i class="fal fa-print"></i></button>';

                     // Gunakan canEdit() dari model untuk menentukan apakah bisa edit/delete
                     if ($row->canEdit()) {
                         // Gunakan window agar pasti global scope
                         $buttons[] = '<button onclick="window.editData(' . $row->id . ')" class="btn btn-warning btn-icon btn-xs rounded-circle" title="Edit"><i class="fal fa-pencil"></i></button>';
                         $buttons[] = '<button onclick="window.deleteData(' . $row->id . ')" class="btn btn-danger btn-icon btn-xs rounded-circle" title="Hapus"><i class="fal fa-trash"></i></button>';
                     }
                     return implode(' ', $buttons);
                 })
                 ->editColumn('tanggal_sr', function ($data) {
                     return tgl($data->tanggal_sr); // Asumsi helper tgl() tersedia
                 })
                 ->editColumn('tipe', function ($data) {
                     return ucfirst($data->tipe);
                 })
                 ->editColumn('status', function ($data) {
                     return ucfirst($data->status);
                 })
                 ->rawColumns(['action'])
                 ->make(true);
         }

         // Initial page load
         return view('pages.simrs.warehouse.stock-request.pharmacy');
     }

    /**
     * Mengambil detail item untuk child row DataTables.
     */
    public function getDetailItems($id)
    {
        $sr = WarehouseStockRequestPharmacy::with(['items', 'items.barang', 'items.satuan'])->findOrFail($id);
        return view("pages.simrs.warehouse.stock-request.partials.sr-detail-childrow", compact('sr'));
    }

    /**
     * Mengambil data item untuk modal pemilihan.
     */
     public function get_item_gudang($asal_gudang_id, $tujuan_gudang_id)
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

             return [
                 'barang_id' => $first_item->pbi->barang_id,
                 'satuan_id' => $first_item->pbi->satuan_id,
                 'barang' => [
                     'id' => $first_item->pbi->barang->id,
                     'nama' => $first_item->pbi->barang->nama,
                     'kode' => $first_item->pbi->barang->kode ?? null,
                 ],
                 'satuan' => [
                     'id' => $first_item->pbi->satuan->id,
                     'nama' => $first_item->pbi->satuan->nama,
                 ],
                 'total_qty' => $total_qty,
             ];
         });

         return response()->json($items_grouped->values()->all());
     }

    /**
     * Show the form for creating a new resource.
     * Modifikasi: Sekarang merender konten modal, bukan halaman penuh.
     */
    public function create()
    {
        return view('pages.simrs.warehouse.stock-request.partials.popup-add-sr-farmasi', [ // [NOTE] Seharusnya ini form-modal-content
            'sr' => null,
            'gudangs' => WarehouseMasterGudang::all(),
            'gudang_asals' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
        ]);
    }

    private function generate_sr_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehouseStockRequestPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . '/SRF/' . $year . $month;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'asal_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tujuan_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tanggal_sr' => 'required|date',
            'tipe' => 'required|in:normal,urgent',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        // [REFACTOR] Validasi item dipindahkan ke dalam transaksi
        $validatedData2 = $request->validate([
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer',
            'keterangan_item.*' => 'nullable|string',
        ]);

        $validatedData1['kode_sr'] = $this->generate_sr_code();
        DB::beginTransaction();

        try {
            $sr = WarehouseStockRequestPharmacy::create($validatedData1);

            // [REFACTOR] Menggunakan createMany untuk efisiensi
            if ($request->has('barang_id')) {
                $items = [];
                foreach ($request->barang_id as $key => $barangId) {
                    $items[] = new WarehouseStockRequestPharmacyItems([
                        'barang_id' => $barangId,
                        'satuan_id' => $request->satuan_id[$key],
                        'qty' => $request->qty[$key],
                        'keterangan' => $request->keterangan_item[$key] ?? null,
                    ]);
                }
                $sr->items()->saveMany($items);
            }

            DB::commit();

            return redirect()->route('warehouse.stock-request.pharmacy.index')->with('success', 'Stock Request berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            // [REFACTOR] Mengembalikan ke halaman create dengan error dan input lama
            return redirect()->route('warehouse.stock-request.pharmacy.create')->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Modifikasi: Menggunakan view yang sama dengan create.
     */
    public function edit(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
    {
        return view('pages.simrs.warehouse.stock-request.partials.popup-add-sr-farmasi', [ // [NOTE] Menggunakan view yang sama dengan create
            'sr' => $warehouseStockRequestPharmacy->findOrFail($id),
            'gudangs' => WarehouseMasterGudang::all(),
            'gudang_asals' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
    {
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'asal_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tujuan_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tanggal_sr' => 'required|date',
            'tipe' => 'required|in:normal,urgent',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer',
            'keterangan_item.*' => 'nullable|string',
            'item_id.*' => 'nullable|exists:warehouse_stock_request_pharmacy_item,id',
        ]);

        DB::beginTransaction();

        try {
            $sr = $warehouseStockRequestPharmacy->findOrFail($id);
            $sr->update($validatedData1);

            $existingItemIds = $sr->items()->pluck('id')->toArray();
            $submittedItemIds = collect($request->item_id)->filter()->toArray(); // Ambil ID item yang dikirim dari form

            // Hapus item yang tidak ada di form
            $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
            if (!empty($itemsToDelete)) {
                WarehouseStockRequestPharmacyItems::whereIn('id', $itemsToDelete)->delete();
            }

            // Update atau buat item baru
            if ($request->has('barang_id')) {
                foreach ($request->barang_id as $key => $barangId) {
                    $itemId = $request->item_id[$key] ?? null;
                    $sr->items()->updateOrCreate(
                        ['id' => $itemId, 'sr_id' => $sr->id],
                        [
                            'barang_id' => $barangId,
                            'satuan_id' => $request->satuan_id[$key],
                            'qty' => $request->qty[$key],
                            'keterangan' => $request->keterangan_item[$key] ?? null,
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->route('warehouse.stock-request.pharmacy.index')->with('success', 'Stock Request berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('warehouse.stock-request.pharmacy.edit', $id)->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function print($id)
    {
        return view('pages.simrs.warehouse.stock-request.partials.sr-print-pharmacy', [
            'sr' => WarehouseStockRequestPharmacy::findorfail($id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
     {
         $sr = $warehouseStockRequestPharmacy->findorfail($id);

         // Cek apakah SR sudah memiliki distribusi/pergerakan barang
         $hasDistribution = DB::table('warehouse_distribusi_barang_farmasi')
             ->where('sr_id', $id)
             ->exists();

         if ($hasDistribution) {
             return response()->json([
                 'success' => false,
                 'message' => 'SR sudah memiliki distribusi barang, tidak bisa dihapus!',
             ]);
         }

         try {
             $sr->delete();

             return response()->json([
                 'success' => true,
                 'message' => 'SR berhasil dihapus!',
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => $e->getMessage(),
             ]);
         }
     }
}
