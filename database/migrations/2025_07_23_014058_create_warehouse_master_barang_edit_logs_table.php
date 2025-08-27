<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_master_barang_edit_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("goods_id");
            $table->string("goods_type");
            $table->string("nama_barang");
            $table->string("kode_barang");
            $table->string("keterangan");
            $table->integer("hna");
            $table->boolean("status_aktif");
            $table->foreignId('golongan_id')->nullable()->constrained('warehouse_golongan_barang')->onDelete('cascade');
            $table->foreignId('kelompok_id')->nullable()->constrained('warehouse_kelompok_barang')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_master_barang_edit_logs');
    }
};
