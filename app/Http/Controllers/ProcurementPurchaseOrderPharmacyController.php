<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderPharmacy;
use App\Models\ProcurementPurchaseOrderPharmacyItems;
use App\Models\ProcurementPurchaseRequestPharmacyItems;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProcurementPurchaseOrderPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProcurementPurchaseOrderPharmacy::with(['supplier', 'user.employee'])->select('procurement_purchase_order_pharmacy.*');

            // Apply filters
            if ($request->filled('kode_po')) {
                $query->where('kode_po', 'like', '%' . $request->kode_po . '%');
            }
            if ($request->filled('approval')) {
                $query->where('approval', $request->approval);
            }
            if ($request->filled('is_auto')) {
                $query->where('is_auto', $request->is_auto);
            }
            if ($request->filled('tanggal_po')) {
                $dates = explode(' - ', $request->tanggal_po);
                if (count($dates) == 2) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                    $query->whereBetween('tanggal_po', [$startDate, $endDate]);
                }
            }
            if ($request->filled('nama_barang')) {
                $query->whereHas('items', function ($q) use ($request) {
                    $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
                });
            }

            return DataTables::of($query)
                ->addColumn('detail', function ($row) {
                    return '<button class="btn btn-primary btn-xs btn-detail" data-id="' . $row->id . '"><i class="fal fa-eye"></i></button>';
                })
                ->editColumn('tanggal_po', function ($row) {
                    return function_exists('tgl') ? tgl($row->tanggal_po) : $row->tanggal_po;
                })
                ->addColumn('supplier_name', function ($row) {
                    return $row->supplier->nama ?? 'N/A';
                })
                ->addColumn('user_entry', function ($row) {
                    return $row->user->employee->fullname ?? 'N/A';
                })
                ->editColumn('tipe', function ($row) {
                    return ucfirst($row->tipe);
                })
                ->editColumn('nominal', function ($row) {
                    return function_exists('rp') ? rp($row->nominal) : $row->nominal;
                })
                ->addColumn('status_approval', function ($row) {
                    $headStatus = '';
                    switch ($row->approval) {
                        case 'approve':
                            $headStatus = '<i class="fas fa-cog fa-spin text-success" title="Head: Approved"></i>';
                            break;
                        default:
                            $headStatus = '<i class="fas fa-cog fa-spin text-danger" title="Head: ' . ucfirst($row->approval) . '"></i>';
                            break;
                    }

                    $ceoStatus = '';
                    switch ($row->approval_ceo) {
                        case 'approve':
                            $ceoStatus = '<i class="fas fa-cog fa-spin text-success" title="CEO: Approved"></i>';
                            break;
                        default:
                            $ceoStatus = '<i class="fas fa-cog fa-spin text-danger" title="CEO: ' . ucfirst($row->approval_ceo) . '"></i>';
                            break;
                    }
                    return '<div>' . $headStatus . ' ' . $ceoStatus . '</div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm print-btn" title="Print" data-id="' . $row->id . '"><i class="fal fa-print"></i></a> ';
                    if ($row->approval_ceo != 'approve') {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit-btn" title="Edit" data-id="' . $row->id . '"><i class="fal fa-pencil"></i></a> ';
                    }
                    if ($row->status != 'final' && $row->status != 'revision') {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" title="Hapus" data-id="' . $row->id . '"><i class="fal fa-trash"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['detail', 'status_approval', 'action'])
                ->make(true);
        }

        return view('pages.simrs.procurement.purchase-order.pharmacy');
    }

    /**
     * Function to get detail items for child row
     */
    public function getDetail($id)
    {
        $po = ProcurementPurchaseOrderPharmacy::with('items.barang')->findOrFail($id);
        return view('pages.simrs.procurement.purchase-order.partials.po-detail-childrow', compact('po'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.simrs.procurement.purchase-order.partials.popup-add-po-farmasi', [
            'suppliers' => WarehouseSupplier::all(),
            'barangs' => WarehouseBarangFarmasi::all(),
        ]);
    }

    public function get_items(Request $request)
    {
        $validatedData = $request->validate([
            'sumber_item' => 'required|in:npr,pr',
            'tipe_pr' => 'required|in:all,normal,urgent',
        ]);

        if ($validatedData['sumber_item'] == 'npr') {
            return view('pages.simrs.procurement.purchase-order.partials.table-items-non-pr-pharmacy', [
                'items' => WarehouseBarangFarmasi::all(),
            ]);
        }

        $query = ProcurementPurchaseRequestPharmacyItems::query()->with(['pr']);

        $query->where(function ($query) {
            $query->whereColumn('ordered_qty', '<', 'approved_qty')
                ->orWhereNull('ordered_qty');
        });

        if ($validatedData['tipe_pr'] != 'all') {
            $query->whereHas('pr', function ($q) use ($validatedData) {
                $q->where('tipe', $validatedData['tipe_pr']);
            });
        }

        $pris = $query->get();

        return view('pages.simrs.procurement.purchase-order.partials.table-items-pr-pharmacy', [
            'pris' => $pris,
        ]);
    }

    private function generate_po_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = ProcurementPurchaseOrderPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . '/FRPO/' . $year . $month;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData1 = $request->validate([
            'tanggal_po' => 'required|date',
            'tanggal_kirim' => 'nullable|date',
            'pic_terima' => 'nullable|string',
            'tipe_top' => 'required|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG',
            'top' => 'required|in:COD,7HARI,14HARI,21HARI,24HARI,30HARI,37HARI,40HARI,45HARI',
            'user_id' => 'required|exists:users,id',
            'ppn' => 'required|integer',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'tipe' => 'required|in:normal,urgent',
            'nominal' => 'required|integer',
            'status' => 'required|in:draft,final,reviewed',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|string',
            'nama_barang' => 'required|array',
            'nama_barang.*' => 'required|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'unit_barang' => 'required|array',
            'unit_barang.*' => 'required|string',
            'pri_id' => 'required|array',
            'pri_id.*' => 'nullable|exists:procurement_purchase_request_pharmacy_items,id',
            'qty' => 'required|array',
            'qty.*' => 'required|integer',
            'qty_bonus' => 'required|array',
            'qty_bonus.*' => 'required|integer',
            'hna' => 'required|array',
            'hna.*' => 'required|integer',
            'discount_nominal' => 'required|array',
            'discount_nominal.*' => 'required|integer',
        ]);

        $validatedData1['kode_po'] = $this->generate_po_code();

        DB::beginTransaction();
        try {
            $po = ProcurementPurchaseOrderPharmacy::create($validatedData1);
            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                ProcurementPurchaseOrderPharmacyItems::create([
                    'po_id' => $po->id,
                    'pri_id' => $validatedData2['pri_id'][$key],
                    'barang_id' => $validatedData2['barang_id'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'harga_barang' => $validatedData2['hna'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'qty_bonus' => $validatedData2['qty_bonus'][$key],
                    'discount_nominal' => $validatedData2['discount_nominal'][$key],
                    'subtotal' => ($validatedData2['hna'][$key] * $validatedData2['qty'][$key]) - $validatedData2['discount_nominal'][$key],
                ]);
            }

            if (isset($validatedData2['pri_id'][$key])) {
                $pri = ProcurementPurchaseRequestPharmacyItems::findorfail($validatedData2['pri_id'][$key]);
                $pri->increment('ordered_qty', $validatedData2['qty'][$key]);
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
        return view('pages.simrs.procurement.purchase-order.partials.po-print-pharmacy', [
            'po' => ProcurementPurchaseOrderPharmacy::findorfail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcurementPurchaseOrderPharmacy $poocurementPurchaseOrderPharmacy, $id)
    {
        return view('pages.simrs.procurement.purchase-order.partials.popup-edit-po-farmasi', [
            'po' => $poocurementPurchaseOrderPharmacy::findorfail($id),
            'suppliers' => WarehouseSupplier::all(),
            'barangs' => WarehouseBarangFarmasi::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validatedData1 = $request->validate([
            'tanggal_po'        => 'required|date',
            'tanggal_kirim'     => 'nullable|date',
            'kode_po'           => 'required|string',
            'pic_terima'        => 'nullable|string',
            'tipe_top'          => 'required|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG',
            'top'               => 'required|in:COD,7HARI,14HARI,21HARI,24HARI,30HARI,37HARI,40HARI,45HARI',
            'user_id'           => 'required|exists:users,id',
            'ppn'               => 'required|integer',
            'supplier_id'       => 'required|exists:warehouse_supplier,id',
            'tipe'              => 'required|in:normal,urgent',
            'nominal'           => 'required|integer',
            'status'            => 'required|in:draft,final,reviewed',
            'keterangan'        => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'kode_barang'           => 'required|array',
            'kode_barang.*'         => 'required|string',
            'nama_barang'           => 'required|array',
            'nama_barang.*'         => 'required|string',
            'barang_id'             => 'required|array',
            'barang_id.*'           => 'required|exists:warehouse_barang_farmasi,id',
            'unit_barang'           => 'required|array',
            'unit_barang.*'         => 'required|string',
            'pri_id'                => 'required|array',
            'pri_id.*'              => 'nullable|exists:procurement_purchase_request_pharmacy_items,id',
            'qty'                   => 'required|array',
            'qty.*'                 => 'required|integer',
            'qty_bonus'             => 'required|array',
            'qty_bonus.*'           => 'required|integer',
            'hna'                   => 'required|array',
            'hna.*'                 => 'required|integer',
            'discount_nominal'      => 'required|array',
            'discount_nominal.*'    => 'required|integer',
            'item_id'               => 'nullable|array',
            'item_id.*'             => 'integer',
        ]);

        DB::beginTransaction();
        try {
            $po = \App\Models\ProcurementPurchaseOrderPharmacy::findOrFail($id);
            $po->update($validatedData1);

            // Reset approval flags if status moved to final + revision state.
            if ($validatedData1['status'] === 'final') {
                if ($po->approval === 'revision') {
                    $po->update(['approval' => 'unreviewed']);
                } elseif ($po->approval_ceo === 'revision') {
                    $po->update(['approval' => 'unreviewed', 'approval_ceo' => 'unreviewed']);
                }
            }

            // Simpan ID yang akan dihapus ke dalam sebuah variabel
            $deletedIds = $request->input('deleted_items', []);

            // Hapus item yang ditandai untuk dihapus, restore ordered_qty jika perlu
            if (!empty($deletedIds)) {
                $itemsToDelete = \App\Models\ProcurementPurchaseOrderPharmacyItems::where('po_id', $po->id)
                    ->whereIn('id', $deletedIds)
                    ->get();

                foreach ($itemsToDelete as $item) {
                    if ($item->pri_id) {
                        $prItem = \App\Models\ProcurementPurchaseRequestPharmacyItems::find($item->pri_id);
                        if ($prItem) {
                            $prItem->decrement('ordered_qty', $item->qty);
                        }
                    }
                }
                // Hapus setelah restore qty
                \App\Models\ProcurementPurchaseOrderPharmacyItems::whereIn('id', $deletedIds)
                    ->where('po_id', $po->id)
                    ->delete();
            }

            // Proses item - update/create, skip jika masuk deletedIds
            foreach ($validatedData2['barang_id'] as $key => $barang_id) {

                // Lewati jika item ini ada pada deletedIds
                if (isset($request->item_id[$key]) && in_array($request->item_id[$key], $deletedIds)) {
                    continue;
                }

                $attributes = [
                    'po_id'             => $po->id,
                    'pri_id'            => $validatedData2['pri_id'][$key],
                    'barang_id'         => $validatedData2['barang_id'][$key],
                    'kode_barang'       => $validatedData2['kode_barang'][$key],
                    'nama_barang'       => $validatedData2['nama_barang'][$key],
                    'unit_barang'       => $validatedData2['unit_barang'][$key],
                    'harga_barang'      => $validatedData2['hna'][$key],
                    'qty'               => $validatedData2['qty'][$key],
                    'qty_bonus'         => $validatedData2['qty_bonus'][$key],
                    'discount_nominal'  => $validatedData2['discount_nominal'][$key],
                    'subtotal'          => ($validatedData2['hna'][$key] * $validatedData2['qty'][$key]) - $validatedData2['discount_nominal'][$key],
                ];

                if (isset($request->item_id[$key]) && !empty($request->item_id[$key])) {
                    // UPDATE item yang sudah ada
                    $poi = \App\Models\ProcurementPurchaseOrderPharmacyItems::findOrFail($request->item_id[$key]);
                    if ($poi->qty != $attributes['qty'] && $poi->pri_id) {
                        $diff = $attributes['qty'] - $poi->qty;
                        $prItem = \App\Models\ProcurementPurchaseRequestPharmacyItems::find($poi->pri_id);
                        if ($prItem) {
                            $prItem->increment('ordered_qty', $diff);
                        }
                    }
                    $poi->update($attributes);
                } else {
                    // CREATE item baru
                    $poi = $po->items()->create($attributes);
                    if ($poi->pri_id) {
                        $prItem = \App\Models\ProcurementPurchaseRequestPharmacyItems::find($poi->pri_id);
                        if ($prItem) {
                            $prItem->increment('ordered_qty', $poi->qty);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcurementPurchaseOrderPharmacy $procurementPurchaseOrderPharmacy, $id)
    {
        $po = $procurementPurchaseOrderPharmacy->findorfail($id);
        if ($po->status != 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'PO sudah final, tidak bisa dihapus!',
            ]);
        }

        try {
            $po->delete();

            return response()->json([
                'success' => true,
                'message' => 'PO berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
