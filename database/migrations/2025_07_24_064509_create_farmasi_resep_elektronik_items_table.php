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
        Schema::create('farmasi_resep_elektronik_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('re_id')->constrained('farmasi_resep_elektroniks')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('barang_id')->constrained('warehouse_barang_farmasi')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('subtotal');
            // string signa, instruksi
            $table->string('signa');
            $table->string('instruksi');
            $table->boolean('billed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_elektronik_items');
    }
};
