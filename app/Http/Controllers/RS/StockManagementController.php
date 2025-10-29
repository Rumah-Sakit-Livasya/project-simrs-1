<?php

namespace App\Http\Controllers\RS;

use App\Http\Controllers\Controller;
use App\Models\RS\CurrentStock;
use App\Models\RS\ProjectBuildItem;
use App\Models\RS\StockLedger;
use App\Models\WarehouseMasterGudang;
use App\Services\StockManagementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class StockManagementController extends Controller
{
    protected StockManagementService $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectBuildItem::with(['kategori', 'satuan'])
                ->leftJoin('current_stocks', 'project_build_items.id', '=', 'current_stocks.project_build_item_id')
                ->select(
                    'project_build_items.id',
                    'project_build_items.item_code',
                    'project_build_items.item_name',
                    'project_build_items.kategori_id',
                    'project_build_items.satuan_id'
                )
                ->groupBy(
                    'project_build_items.id',
                    'project_build_items.item_code',
                    'project_build_items.item_name',
                    'project_build_items.kategori_id',
                    'project_build_items.satuan_id'
                )
                ->selectRaw('SUM(current_stocks.quantity) as total_stock');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $detailsBtn = '<button class="btn btn-xs btn-info view-details-btn mr-1" data-id="' . $row->id . '">Stok/Gudang</button>';
                    $cardBtn = '<a href="' . route('stock-management.card', $row->id) . '" class="btn btn-xs btn-primary">Kartu Stok</a>';
                    return $detailsBtn . $cardBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $items = ProjectBuildItem::where('is_active', true)->orderBy('item_name')->get();
        $gudangs = WarehouseMasterGudang::all();

        return view('app-type.rs.stock-management.index', compact('items', 'gudangs'));
    }

    /**
     * Handle manual stock in from the monitoring page.
     */
    public function manualStockIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_build_item_id' => 'required|exists:project_build_items,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'quantity' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        try {
            $this->stockService->updateStock(
                'in',
                $validated['project_build_item_id'],
                $validated['gudang_id'],
                $validated['quantity'],
                "Penerimaan Manual: " . $validated['description']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['success' => 'Stok masuk berhasil dicatat.']);
    }

    /**
     * Mengambil detail stok per gudang untuk item tertentu.
     */
    public function getStockDetails(ProjectBuildItem $projectBuildItem)
    {
        $stockDetails = CurrentStock::where('project_build_item_id', $projectBuildItem->id)
            ->with('warehouseMasterGudang')
            ->where('quantity', '>', 0)
            ->get();

        return view('app-type.rs.stock-management.partials.stock_details', compact('stockDetails'));
    }

    public function showStockCard(ProjectBuildItem $projectBuildItem)
    {
        $ledgers = StockLedger::where('project_build_item_id', $projectBuildItem->id)
            ->with(['gudang', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('app-type.rs.stock-management.card', [
            'item' => $projectBuildItem,
            'ledgers' => $ledgers
        ]);
    }
}
