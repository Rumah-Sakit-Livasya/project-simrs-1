<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseStockOpnameGudang;
use App\Models\WarehouseStockOpnameItems;
use App\Services\GoodsStockService;
use App\Services\IncreaseDecreaseStockArguments;
use App\Services\TransferStockArguments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockOpnameFinal extends Controller
{
    protected GoodsStockService $goodsStockService;

    public function __construct(GoodsStockService $goodsStockService)
    {
        $this->goodsStockService = $goodsStockService;
        $this->goodsStockService->controller = $this::class;
    }

    /**
     * Display a listing of pending opname gudang for finalization.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.index", [
            'ogs'       => WarehouseStockOpnameGudang::whereNull('finish')->get(),
            'kategoris' => WarehouseKategoriBarang::all(),
            'satuans'   => WarehouseSatuanBarang::all(),
        ]);
    }

    /**
     * Return only those items that have been counted (draft or final)
     * with their movement/frozen data, for a given opname.
     *
     * @param  int  $id  opname gudang ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_opname_items($id)
    {
        $opname   = WarehouseStockOpnameGudang::findOrFail($id);
        $gudangId = $opname->gudang->id;

        $itemsF  = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();

        $all     = $this->attachOpnameData($itemsF, $opname)
            ->merge($this->attachOpnameData($itemsNF, $opname));

        // Only keep items that have an opname record
        $filtered = $all->filter(fn($item) => $item->opname !== null);

        // Sort by nama_barang
        $sorted = $filtered->sortBy(fn($item) => $item->pbi->nama_barang)
            ->values()
            ->toArray();

        return response()->json($sorted);
    }

    /**
     * Print the selisih report for final opname.
     *
     * @param  int  $sog_id
     * @return \Illuminate\View\View
     */
    public function print_selisih($sog_id)
    {
        $opname   = WarehouseStockOpnameGudang::findOrFail($sog_id);
        $gudangId = $opname->gudang->id;

        $itemsF  = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();

        $items = $this->attachOpnameData($itemsF, $opname)
            ->merge($this->attachOpnameData($itemsNF, $opname))
            ->all();

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.partials.so-print-selisih", [
            'items' => $items,
            'sog'   => $opname,
        ]);
    }

    /**
     * Print the full SO report for final opname.
     *
     * @param  int  $sog_id
     * @return \Illuminate\View\View
     */
    public function print_so($sog_id)
    {
        $opname   = WarehouseStockOpnameGudang::findOrFail($sog_id);
        $gudangId = $opname->gudang->id;

        $itemsF  = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();
        $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
            ->where('gudang_id', $gudangId)->get();

        $items = $this->attachOpnameData($itemsF, $opname)
            ->merge($this->attachOpnameData($itemsNF, $opname))
            ->all();

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.partials.so-print-so", [
            'items' => $items,
            'sog'   => $opname,
        ]);
    }

    /**
     * Finalize selected opname items: mark as final and apply qty to stored items.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sog_id'  => 'required|exists:warehouse_stock_opname_gudang,id',
            'user_id' => 'required|exists:users,id',
            'sio_id.*' => 'required|exists:warehouse_stock_opname_item,id',
        ]);

        DB::beginTransaction();
        try {
            $opname = WarehouseStockOpnameGudang::findOrFail($data['sog_id']);
            $user = User::findOrFail($data['user_id']);

            if ($opname->finish !== null) {
                throw new \Exception('Opname sudah selesai');
            }

            foreach ($data['sio_id'] as $sioId) {
                $sio = WarehouseStockOpnameItems::findOrFail($sioId);

                // skip if already final
                if ($sio->status === 'final') {
                    continue;
                }

                // calculate movement since start
                $movement = $this->calculateMovement($sio->stored, $opname->start);

                // ensure no negative resulting stock
                if ($sio->qty + $movement < 0) {
                    throw new \Exception('Stock tidak bisa kurang dari 0');
                }

                // finalize this opname item
                $sio->status = 'final';
                $sio->save();

                // apply to actual stored record
                $stored = $sio->stored;
                // $stored->qty = $sio->qty + $movement;
                // $stored->save();

                // use the GoodsStockService
                $args = new IncreaseDecreaseStockArguments($user, $sio, $stored, $movement);
                if ($movement < 0) {
                    $this->goodsStockService->decreaseStock($args);
                } else {
                    $this->goodsStockService->increaseStock($args);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Stub methods to preserve API
    public function show(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Sum qty‐movement of an item since a timestamp.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $item
     * @param  \Carbon\Carbon|string               $since
     * @return int
     */
    private function calculateMovement($item, $since)
    {
        return $item->audits()
            ->where('created_at', '>', $since)
            ->get()
            ->reduce(function ($carry, $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;
                if (isset($old['qty'], $new['qty'])) {
                    return $carry + ($new['qty'] - $old['qty']);
                }
                return $carry;
            }, 0);
    }

    /**
     * Annotate a collection of stored‐items with:
     *  - type ('f' or 'nf'),
     *  - related opname record,
     *  - movement,
     *  - frozen (qty minus movement and any final‐discount).
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  WarehouseStockOpnameGudang      $opname
     * @return \Illuminate\Support\Collection
     */
    private function attachOpnameData($items, WarehouseStockOpnameGudang $opname)
    {
        return $items->map(function ($item) use ($opname) {
            $type = $item instanceof StoredBarangNonFarmasi ? 'nf' : 'f';
            $item->type   = $type;
            $item->opname = null;

            $sio = WarehouseStockOpnameItems::where('sog_id', $opname->id)
                ->where("si_{$type}_id", $item->id)
                ->first();

            $auditsQuery = $item->audits()->where('created_at', '>', $opname->start);
            $discountQty = 0;

            if ($sio) {
                $item->opname = $sio;

                if ($sio->status === 'final') {
                    // only count movements before final update
                    $auditsQuery = $auditsQuery->where('created_at', '<', $sio->updated_at);

                    // determine discount from last audit before finalization
                    $last = $item->audits()
                        ->where('created_at', '>', $opname->start)
                        ->where('created_at', '<=', $sio->updated_at)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($last && isset($last->old_values['qty'], $last->new_values['qty'])) {
                        $discountQty = $last->new_values['qty'] - $last->old_values['qty'];
                    }
                }
            }

            $movement = $auditsQuery->get()->reduce(function ($carry, $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;
                return $carry + ((isset($old['qty'], $new['qty'])) ? ($new['qty'] - $old['qty']) : 0);
            }, 0);

            $item->movement = $movement;
            $item->frozen   = $item->qty - $movement - $discountQty;

            return $item;
        });
    }
}
