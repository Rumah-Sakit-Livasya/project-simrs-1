<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseStockOpnameGudang;
use App\Models\WarehouseStockOpnameItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockOpnameDraft extends Controller
{
    /**
     * Display the listing of draft stock opname gudang.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.index', [
            'ogs' => WarehouseStockOpnameGudang::whereNull('finish')->get(),
            'kategoris' => WarehouseKategoriBarang::all(),
            'satuans' => WarehouseSatuanBarang::all(),
        ]);
    }

    /**
     * Compute the quantity movement of a single item since the opname start.
     *
     * @param  string  $type  'f' for farmasi, 'nf' for non‐farmasi
     * @param  int  $opname_id
     * @param  int  $si_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_opname_item_movement($type, $opname_id, $si_id)
    {
        $item = $this->findStoredItem($type, $si_id);
        $opname = WarehouseStockOpnameGudang::findOrFail($opname_id);

        $movement = $this->calculateMovement($item, $opname->start);

        return response()->json(['movement' => $movement]);
    }

    /**
     * Return all items (farmasi & non‐farmasi) for an opname,
     * enriched with frozen stock, movement, and attached opname info.
     *
     * @param  int  $id  opname gudang ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_opname_items($id)
    {
        $opname = WarehouseStockOpnameGudang::findOrFail($id);
        $gudangId = $opname->gudang->id;

        $itemsF = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();

        $itemsF = $this->attachOpnameData($itemsF, $opname);
        $itemsNF = $this->attachOpnameData($itemsNF, $opname);

        // Merge and sort by pbi.nama_barang
        $merged = array_merge($itemsF->toArray(), $itemsNF->toArray());
        usort($merged, function ($a, $b) {
            return strcmp($a['pbi']['nama_barang'], $b['pbi']['nama_barang']);
        });

        return response()->json($merged);
    }

    /**
     * Store or update draft adjustments for multiple opname items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Decode JSON string if present
        if ($request->has('drafts')) {
            $request['drafts'] = json_decode($request['drafts']);
        } else {
            return response()->json(['message' => 'Draft is required'], 400);
        }

        $request->validate([
            'drafts' => 'required|array',
            'sog_id' => 'required|exists:warehouse_stock_opname_gudang,id',
            'column' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $opname = WarehouseStockOpnameGudang::findOrFail($request['sog_id']);

            foreach ($request['drafts'] as $draft) {
                $item = $this->findStoredItemFromColumn($request['column'], $draft->si_id);
                $movement = $this->calculateMovement($item, $opname->start);

                if ($draft->qty + $movement < 0) {
                    throw new \Exception('Stock tidak bisa kurang dari 0');
                }

                $criteria = [
                    'sog_id' => $request['sog_id'],
                    $request['column'] => $draft->si_id,
                ];

                $wsItem = WarehouseStockOpnameItems::firstWhere($criteria);

                if ($wsItem) {
                    // Skip if already finalized
                    if ($wsItem->status === 'final') {
                        continue;
                    }
                    $wsItem->update([
                        'qty' => $draft->qty,
                        'keterangan' => $draft->keterangan,
                        'user_id' => $request['user_id'],
                    ]);
                } else {
                    WarehouseStockOpnameItems::create([
                        'kode_so' => $this->generate_so_code(),
                        'sog_id' => $request['sog_id'],
                        $request['column'] => $draft->si_id,
                        'qty' => $draft->qty,
                        'keterangan' => $draft->keterangan,
                        'user_id' => $request['user_id'],
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Print the difference report (selisih) for a given opname.
     *
     * @param  int  $sog_id
     * @return \Illuminate\View\View
     */
    public function print_selisih($sog_id)
    {
        $opname = WarehouseStockOpnameGudang::findOrFail($sog_id);
        $gudangId = $opname->gudang->id;

        $itemsF = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();

        $itemsF = $this->attachOpnameData($itemsF, $opname);
        $itemsNF = $this->attachOpnameData($itemsNF, $opname);

        $items = array_merge($itemsF->all(), $itemsNF->all());

        return view('pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.partials.so-print-selisih', [
            'items' => $items,
            'sog' => $opname,
        ]);
    }

    /**
     * Print the stock opname (SO) report.
     *
     * @param  int  $sog_id
     * @return \Illuminate\View\View
     */
    public function print_so($sog_id)
    {
        $opname = WarehouseStockOpnameGudang::findOrFail($sog_id);
        $gudangId = $opname->gudang->id;

        $itemsF = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)
            ->get();

        $itemsF = $this->attachOpnameData($itemsF, $opname);
        $itemsNF = $this->attachOpnameData($itemsNF, $opname);

        $items = array_merge($itemsF->all(), $itemsNF->all());

        return view('pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.partials.so-print-so', [
            'items' => $items,
            'sog' => $opname,
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helper methods
    // -------------------------------------------------------------------------

    /**
     * Generate a unique Stock Opname code based on current year, month, and count.
     *
     * @return string Stock Opname code.
     */
    private function generate_so_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehouseStockOpnameItems::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count.'/SO'.$year.$month;
    }

    /**
     * Find a stored item instance by type.
     *
     * @param  string  $type  'f' or 'nf'
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function findStoredItem(string $type, int $id)
    {
        if ($type === 'nf') {
            return StoredBarangNonFarmasi::findOrFail($id);
        }

        return StoredBarangFarmasi::findOrFail($id);
    }

    /**
     * Given a column name, find the corresponding stored‐item model.
     *
     * @param  string  $column  'si_f_id' or 'si_nf_id'
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function findStoredItemFromColumn(string $column, int $siId)
    {
        if ($column === 'si_nf_id') {
            return StoredBarangNonFarmasi::findOrFail($siId);
        }

        return StoredBarangFarmasi::findOrFail($siId);
    }

    /**
     * Calculate total movement (sum of qty diffs) since a given timestamp.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $item
     * @param  \Carbon\Carbon|string  $since
     * @return int
     */
    private function calculateMovement($item, $since)
    {
        $audits = $item->audits()->where('created_at', '>', $since)->get();
        $movement = 0;

        foreach ($audits as $audit) {
            $old = $audit->old_values;
            $new = $audit->new_values;
            if (isset($old['qty'], $new['qty'])) {
                $movement += ($new['qty'] - $old['qty']);
            }
        }

        return $movement;
    }

    /**
     * Attach 'type', 'opname', 'frozen', and 'movement' properties to each item.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return \Illuminate\Support\Collection
     */
    private function attachOpnameData($items, WarehouseStockOpnameGudang $opname)
    {
        return $items->map(function ($item) use ($opname) {
            $typeKey = $item instanceof StoredBarangNonFarmasi ? 'nf' : 'f';
            $item->type = $typeKey;
            $item->opname = null;

            $sogId = $opname->id;
            $siField = 'si_'.$typeKey.'_id';
            $itemOpname = WarehouseStockOpnameItems::where('sog_id', $sogId)
                ->where($siField, $item->id)
                ->first();

            // Limit audits to before finalisation if already finalized
            $auditsQuery = $item->audits()->where('created_at', '>', $opname->start);
            $discountQty = 0;

            if ($itemOpname) {
                $item->opname = $itemOpname;
                if ($itemOpname->status === 'final') {
                    $auditsQuery = $auditsQuery->where('created_at', '<', $itemOpname->updated_at);
                    $lastAudit = $item->audits()
                        ->where('created_at', '>', $opname->start)
                        ->where('created_at', '<=', $itemOpname->updated_at)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($lastAudit && isset($lastAudit->old_values['qty'], $lastAudit->new_values['qty'])) {
                        $discountQty = $lastAudit->new_values['qty'] - $lastAudit->old_values['qty'];
                    }
                }
            }

            // Compute net movement
            $movement = $auditsQuery->get()->reduce(function ($carry, $audit) {
                $ov = $audit->old_values;
                $nv = $audit->new_values;

                return $carry + ((isset($ov['qty'], $nv['qty'])) ? ($nv['qty'] - $ov['qty']) : 0);
            }, 0);

            $item->movement = $movement;
            $item->frozen = $item->qty - $movement - $discountQty;

            return $item;
        });
    }

    // Empty stub methods to preserve API
    public function show(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
