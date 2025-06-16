<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\keuangan\PenerimaanBarangHeader;
use App\Models\ProcurementPurchaseOrderPharmacy;

class PenerimaanBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        // PenerimaanBarangHeader::truncate();

        // GRN 1: Dari Kimia Farma (Belum AP)
        PenerimaanBarangHeader::create([
            'no_grn' => 'GRN/24/KF/001',
            'tanggal_penerimaan' => now()->subDays(5),
            'supplier_id' => 1, // ID Kimia Farma
            'status_ap' => 'Belum AP',
            'total_nilai_barang' => 1500000.00,
            'user_penerima_id' => 1, // User dengan ID 1
            'purchasable_id' => 1,   // Merujuk ke PO/PHARM/24/001
            'purchasable_type' => ProcurementPurchaseOrderPharmacy::class,
        ]);

        // GRN 2: Dari Kimia Farma juga (Belum AP)
        PenerimaanBarangHeader::create([
            'no_grn' => 'GRN/24/KF/002',
            'tanggal_penerimaan' => now()->subDays(4),
            'supplier_id' => 1,
            'status_ap' => 'Belum AP',
            'total_nilai_barang' => 750000.00,
            'user_penerima_id' => 1,
            'purchasable_id' => 1,
            'purchasable_type' => ProcurementPurchaseOrderPharmacy::class,
        ]);

        // GRN 3: Dari Indofarma (Belum AP)
        PenerimaanBarangHeader::create([
            'no_grn' => 'GRN/24/IF/001',
            'tanggal_penerimaan' => now()->subDays(3),
            'supplier_id' => 2, // ID Indofarma
            'status_ap' => 'Belum AP',
            'total_nilai_barang' => 2200000.00,
            'user_penerima_id' => 1,
            'purchasable_id' => 2,   // Merujuk ke PO/PHARM/24/002
            'purchasable_type' => ProcurementPurchaseOrderPharmacy::class,
        ]);

        // GRN 4: Dari Indofarma (SUDAH AP, untuk pengujian filter)
        PenerimaanBarangHeader::create([
            'no_grn' => 'GRN/24/IF/002',
            'tanggal_penerimaan' => now()->subDays(2),
            'supplier_id' => 2,
            'status_ap' => 'Sudah AP', // Status berbeda
            'total_nilai_barang' => 300000.00,
            'user_penerima_id' => 1,
            'purchasable_id' => 2,
            'purchasable_type' => ProcurementPurchaseOrderPharmacy::class,
        ]);
    }
}
