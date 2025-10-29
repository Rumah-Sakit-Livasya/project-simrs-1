<?php

namespace App\Services;

use App\Models\RS\CurrentStock;
use App\Models\RS\StockLedger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockManagementService
{
    public function updateStock(string $type, int $itemId, int $gudangId, float $quantity, string $description, $reference = null)
    {
        DB::transaction(function () use ($type, $itemId, $gudangId, $quantity, $description, $reference) {
            $currentStock = CurrentStock::where('project_build_item_id', $itemId)
                ->where('gudang_id', $gudangId)
                ->lockForUpdate()
                ->first();

            if (!$currentStock) {
                $currentStock = CurrentStock::create([
                    'project_build_item_id' => $itemId,
                    'gudang_id' => $gudangId,
                    'quantity' => 0,
                ]);
            }

            $stockBefore = $currentStock->quantity;

            if ($type === 'in') {
                $stockAfter = $stockBefore + $quantity;
                $currentStock->increment('quantity', $quantity);
            } elseif ($type === 'out') {
                if ($stockBefore < $quantity) {
                    throw new \Exception("Stok untuk item di gudang ini tidak mencukupi (Stok: {$stockBefore}, Dibutuhkan: {$quantity}).");
                }
                $stockAfter = $stockBefore - $quantity;
                $currentStock->decrement('quantity', $quantity);
            } else {
                throw new \Exception("Tipe transaksi tidak valid.");
            }

            StockLedger::create([
                'project_build_item_id' => $itemId,
                'gudang_id' => $gudangId,
                'type' => $type,
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'description' => $description,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'user_id' => Auth::id() ?? User::first()->id, // Fallback user jika dijalankan dari console
            ]);
        });
    }
}
