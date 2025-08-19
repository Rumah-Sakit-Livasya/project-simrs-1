<?php

namespace App\Observers;

use App\Models\Inspection;
use App\Models\VehicleService;
use Illuminate\Support\Facades\Log;

class InspectionObserver
{
    /**
     * Handle the Inspection "created" event.
     *
     * @param  \App\Models\Inspection  $inspection
     * @return void
     */
    public function created(Inspection $inspection)
    {
        // LOG 1: Cek apakah observer terpicu
        // Log::info('InspectionObserver terpicu untuk Inspeksi ID: ' . $inspection->id);

        // $damagedItems = $inspection->results()->with('item')->where('status', 'Rusak')->get();

        // // LOG 2: Cek apakah ada item rusak yang ditemukan
        // Log::info('Jumlah item rusak ditemukan: ' . $damagedItems->count());

        // // 1. Ambil semua hasil inspeksi yang statusnya 'Rusak' dari inspeksi yang BARU SAJA dibuat.
        // //    Kita gunakan 'with('item')' (eager loading) agar lebih efisien dan tidak terjadi N+1 query.
        // $damagedItems = $inspection->results()->with('item')->where('status', 'Rusak')->get();

        // // 2. Loop melalui setiap item yang rusak.
        // foreach ($damagedItems as $result) {
        //     // 3. Untuk setiap item yang rusak, buat tiket perbaikan baru (VehicleService).
        //     VehicleService::create([
        //         // ID Kendaraan yang rusak
        //         'internal_vehicle_id' => $result->internal_vehicle_id,

        //         // ID Pelapor adalah petugas yang melakukan inspeksi
        //         'reported_by_id' => $inspection->inspector_id,

        //         // ID hasil inspeksi sebagai referensi/sumber masalah
        //         'inspection_result_id' => $result->id,

        //         // Buat deskripsi masalah yang jelas dan informatif
        //         'description_of_issue' => "[Temuan Inspeksi] - " . ($result->item->name ?? 'Item tidak diketahui') . ": " . ($result->notes ?: 'Tidak ada catatan tambahan.'),

        //         // Kolom-kolom lain akan diisi nanti saat perbaikan dilakukan.
        //         // Status defaultnya adalah 'Open' sesuai schema database Anda.
        //         'workshop_vendor_id' => null,
        //         'service_date' => null,
        //         'work_done' => null,
        //     ]);
        // }
    }

    // Method lain (updated, deleted, etc.) bisa Anda biarkan kosong jika tidak digunakan.
}
