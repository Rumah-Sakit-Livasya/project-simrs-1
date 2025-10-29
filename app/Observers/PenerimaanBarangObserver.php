<?php

namespace App\Observers;

use App\Models\WarehousePenerimaanBarangNonFarmasiItems;
use App\Services\StockManagementService;
use App\Models\RS\ProjectBuildItem; // Import model ProjectBuildItem

class PenerimaanBarangObserver
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Handle the WarehousePenerimaanBarangNonFarmasiItems "created" event.
     */
    public function created(WarehousePenerimaanBarangNonFarmasiItems $item): void
    {
        // ASUMSI: Ada relasi dari WarehouseBarangNonFarmasi ke ProjectBuildItem
        // Kita perlu mencari ProjectBuildItem yang sesuai
        $projectItem = ProjectBuildItem::where('item_code', $item->item->kode_barang)->first();

        // Hanya proses jika item master proyeknya ditemukan
        if ($projectItem) {
            $gudangId = $item->pb->gudang_id;
            $quantity = $item->qty;
            $description = "Penerimaan Barang dari PO No: " . ($item->pb->po->kode_po ?? 'N/A');

            $this->stockService->updateStock('in', $projectItem->id, $gudangId, $quantity, $description, $item->pb);
        }
    }

    // ... (metode updated, deleted, etc. bisa diisi jika perlu rollback stok)
}
