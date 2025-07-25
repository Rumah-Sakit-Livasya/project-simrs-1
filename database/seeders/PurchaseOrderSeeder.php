<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProcurementPurchaseOrderPharmacy;
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi
        // ProcurementPurchaseOrderPharmacy::truncate();

        // PO 1: Untuk Kimia Farma (ID 1)
        ProcurementPurchaseOrderPharmacy::create([
            'kode_po' => 'PO/PHARM/24/001',
            'user_id' => 1, // Dibuat oleh user dengan ID 1
            'app_user_id' => 1, // Diasumsikan di-approve oleh user yang sama
            'ceo_app_user_id' => null, // Diasumsikan belum perlu approval CEO
            'supplier_id' => 1, // ID PT. Kimia Farma Trading
            'tanggal_po' => Carbon::now()->subDays(10),
            'tanggal_app' => Carbon::now()->subDays(9),
            'tanggal_app_ceo' => null,
            'tanggal_kirim' => Carbon::now()->subDays(2),
            'is_auto' => 0,
            'top' => '14HARI',
            'tipe_top' => 'SETELAH_TERIMA_BARANG',
            'tipe' => 'normal',
            'status' => 'final', // Status harus 'final' agar bisa dibuat GRN
            'approval' => 'approve',
            'approval_ceo' => 'unreviewed',
            'ppn' => 11, // PPN 11%
            'nominal' => 2250000, // Contoh nominal
            'pic_terima' => 'User Gudang',
            'keterangan' => 'Seeder PO untuk Kimia Farma',
            'keterangan_approval' => 'Approved by system seeder.',
            'keterangan_approval_ceo' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // PO 2: Untuk Indofarma (ID 2)
        ProcurementPurchaseOrderPharmacy::create([
            'kode_po' => 'PO/PHARM/24/002',
            'user_id' => 1,
            'app_user_id' => 1,
            'ceo_app_user_id' => null,
            'supplier_id' => 2, // ID PT. Indofarma Global Medica
            'tanggal_po' => Carbon::now()->subDays(8),
            'tanggal_app' => Carbon::now()->subDays(7),
            'tanggal_app_ceo' => null,
            'tanggal_kirim' => Carbon::now()->subDays(1),
            'is_auto' => 0,
            'top' => '30HARI',
            'tipe_top' => 'SETELAH_TERIMA_BARANG',
            'tipe' => 'normal',
            'status' => 'final',
            'approval' => 'approve',
            'approval_ceo' => 'unreviewed',
            'ppn' => 0, // Tanpa PPN
            'nominal' => 3000000,
            'pic_terima' => 'User Gudang',
            'keterangan' => 'Seeder PO untuk Indofarma',
            'keterangan_approval' => 'Approved by system seeder.',
            'keterangan_approval_ceo' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
