<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseStockOpnameGudang;
use App\Models\WarehouseStockOpnameItems;
use Illuminate\Http\Request;

class WarehouseStockOpnameReport extends Controller
{
    /**
     * Display a listing of pending opname gudang for finalization.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = WarehouseStockOpnameGudang::query();
        $filters = ["gudang_id"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_so')) {
            $dateRange = explode(' - ', $request->tanggal_so);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('start', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $sogs = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data from the start of the month until today if no filter is applied
            $sogs = $query->whereBetween('start', [date('Y-m-01 00:00:00'), date('Y-m-d 23:59:59')])->orderBy('created_at', 'asc')->get();
        }

        foreach ($sogs as $so) {
            $itemsF  = StoredBarangFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
                ->where('gudang_id', $so->gudang_id)->get();
            $itemsNF = StoredBarangNonFarmasi::with(['pbi', 'pbi.item', 'pbi.satuan', 'pbi.pb'])
                ->where('gudang_id', $so->gudang_id)->get();

            $so->stored_items = $this->attachOpnameData($itemsF, $so)
                ->merge($this->attachOpnameData($itemsNF, $so))
                ->all();
        }

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.report.index", [
            'gudangs' => WarehouseMasterGudang::all(),
            "sogs" => $sogs
        ]);
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

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.report.partials.so-print-selisih", [
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

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.report.partials.so-print-so", [
            'items' => $items,
            'sog'   => $opname,
        ]);
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
