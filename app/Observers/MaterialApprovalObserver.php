<?php

namespace App\Observers;

use App\Models\RS\MaterialApproval;
use App\Services\StockManagementService;

class MaterialApprovalObserver
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Handle the MaterialApproval "updated" event.
     */
    public function updated(MaterialApproval $materialApproval): void
    {
        // Cek apakah status BARU SAJA diubah menjadi 'Approved'
        if ($materialApproval->wasChanged('status') && $materialApproval->status === 'Approved') {

            // ASUMSI PENTING: Dari gudang mana stok akan dikurangi?
            // Untuk sekarang, kita asumsikan ada gudang utama proyek, misal ID = 1.
            // Di aplikasi nyata, ini mungkin perlu dipilih di form approval.
            $gudangId = 1; // <-- GANTI DENGAN LOGIKA PEMILIHAN GUDANG YANG SESUAI

            $projectItemId = $materialApproval->project_build_item_id;
            $quantity = $materialApproval->quantity;
            $description = "Penggunaan Material untuk Proyek (Approval No: {$materialApproval->id})";

            if ($projectItemId && $quantity > 0) {
                try {
                    $this->stockService->updateStock('out', $projectItemId, $gudangId, $quantity, $description, $materialApproval);
                } catch (\Exception $e) {
                    // Jika stok gagal dikurangi (misal, tidak cukup), apa yang harus dilakukan?
                    // Opsi 1: Batalkan approval (kembalikan status)
                    $materialApproval->status = 'Submitted'; // atau status sebelumnya
                    $materialApproval->remarks .= "\n[SYSTEM] Gagal mengurangi stok: " . $e->getMessage();
                    $materialApproval->saveQuietly(); // Simpan tanpa memicu observer lagi

                    // Opsi 2: Biarkan status approved tapi beri notifikasi
                    // ... (logika notifikasi ke admin gudang) ...
                }
            }
        }
    }
}
