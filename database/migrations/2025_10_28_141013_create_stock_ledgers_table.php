<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();

            // Item yang ditransaksikan
            $table->foreignId('project_build_item_id')->constrained('project_build_items')->onDelete('cascade');

            // Gudang tempat transaksi terjadi
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang')->onDelete('cascade');

            // Tipe Transaksi (IN/OUT)
            $table->enum('type', ['in', 'out']);

            // Kuantitas yang masuk atau keluar
            $table->decimal('quantity', 15, 2);

            // Stok sebelum dan sesudah transaksi
            $table->decimal('stock_before', 15, 2);
            $table->decimal('stock_after', 15, 2);

            // Informasi tambahan
            $table->text('description'); // Contoh: "Penerimaan dari Supplier X" atau "Penggunaan untuk Pemasangan Lantai 1"
            $table->string('reference_type')->nullable(); // Contoh: 'MaterialApproval', 'ManualEntry'
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dari model referensi

            $table->foreignId('user_id')->constrained('users'); // User yang mencatat transaksi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
