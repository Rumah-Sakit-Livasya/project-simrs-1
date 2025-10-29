<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_build_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique()->comment('Kode unik untuk item proyek, misal: MT-001');
            $table->string('item_name');
            $table->text('description')->nullable()->comment('Deskripsi atau spesifikasi umum');

            // Relasi ke tabel master lain yang mungkin sudah ada
            $table->foreignId('kategori_id')->nullable()->constrained('warehouse_kategori_barang')->onDelete('set null');
            $table->foreignId('satuan_id')->nullable()->constrained('warehouse_satuan_barang')->onDelete('set null');

            // Kolom untuk manajemen stok di masa depan
            $table->decimal('current_stock', 15, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_build_items');
    }
};
